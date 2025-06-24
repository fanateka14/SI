<?php

namespace app\controllers;

use app\models\Departement;
use app\models\AffichageBudjetPeriode;
use app\models\Type;
use app\models\Solde;
use Flight;

class BudgetController
{
   public function getBudget()
   {
      // Recuperer les donnees du formulaire
      $dateDeb = Flight::request()->data->dateDeb;
      $dateFin = Flight::request()->data->dateFin;
      $idDept = Flight::request()->data->idDept;
      $intervalle = Flight::request()->data->intervalle;
      $types = Type::getAllType();

      if (!isset($_SESSION['idDept'])) {
         Flight::redirect('/login');
      }

      // Verifier si toutes les donnees sont bien remplies
      if (!empty($dateDeb) && !empty($dateFin) && !empty($idDept) && !empty($intervalle)) {

         // Recuperer la liste des departements
         $departements = Departement::getAllDept($_SESSION['idDept']);

         // Generer les debuts de mois entre les dates fournies
         $moisDebuts = AffichageBudjetPeriode::getDebutsDeMois($dateDeb, $dateFin);

         // Initialiser les donnees des tables
         $tablesData = [];
         // $budgetInitial = Departement::getDepartementById($idDept)->getSoldeInitial()['montant'];
         $soldeModel = new Solde();
         $budgetInitial = $soldeModel->getSolde($_SESSION['idDept'], $dateDeb); // Recuperer le solde initial


         // Pour chaque mois genere, recuperer les donnees
         foreach ($moisDebuts as $index => $mois) {
            $moisFormat = date('Y-m', strtotime($mois)); // Format du mois (YYYY-MM)

            // Appeler la fonction pour recuperer les donnees du budget par mois et annee
            $budgetData = AffichageBudjetPeriode::getBudgetByMonthYear($idDept, date('m', strtotime($mois)), date('Y', strtotime($mois)));

            // Appeler la fonction pour recuperer les totaux des recettes et depenses
            $realisationTotal = AffichageBudjetPeriode::getRealisationTotalByMonthYear($idDept, date('m', strtotime($mois)), date('Y', strtotime($mois)));

            // Preparer les donnees a afficher
            // Ajouter les totaux a chaque mois
            $tablesData[$index] = [
               'mois' => date('F Y', strtotime($mois)), // Format lisible du mois
               'data' => $budgetData,
               'totalRecettes' => $realisationTotal['totalRecettes'],
               'totalDepenses' => $realisationTotal['totalDepenses']
               
           ];
         }

         // Envoi des donnees a la vue
         $data = ['page' => 'budget', 'tablesData' => $tablesData, 'departements' => $departements,'soldeInitial' => $budgetInitial,'datDeb' => $dateDeb,'dateFin' => $dateFin,'types' => $types];
         Flight::render('template', $data);
         return;
      }

      // Si les donnees sont incompletes, on affiche la page sans tableau
      $departements = Departement::getAllDept($_SESSION['idDept']);
      $data = ['page' => 'budget', 'departements' => $departements,'types' => $types];
      Flight::render('template', $data);
   }
}
