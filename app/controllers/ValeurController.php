<?php

namespace app\controllers;

use app\models\Crm;
use app\models\Vente;
use Flight;
use app\models\Valeur;
use app\models\Validation;
use DateTime;

class ValeurController
{
    public function __construct() {

	}
    public function getFormulaireImportCSV()
    {
        Flight::render('importCSV');
    }

    public function doImportCSV()
    {
        // On recupere le fichier CSV directement a partir de l'input
        $file = Flight::request()->files->filePath;

        // Si un fichier a ete trouve
        if ($file) {
            // On recupere le chemin temporaire du fichier telecharge
            $file_path = $file['tmp_name'];

            // Appel a la methode dans la classe Valeur pour importer les donnees du fichier CSV
            $valeurs = Valeur::getListeValeurFromCsv($file_path);

            // Redirection ou retour apres l'importation
            Flight::redirect('/budget');
        } else {
            // Si le fichier n'a pas ete trouve ou telecharge, on affiche une erreur
            Flight::flash('error', 'Aucun fichier telecharge.');
            Flight::redirect('/import');
        }
    }

    public function saveRealisation()
    {
        // Recuperer les donnees du formulaire
        $nomRubrique = Flight::request()->data->nature;
        $idType = Flight::request()->data->type;
        $montant = Flight::request()->data->montant;
        $idDept = $_SESSION['idDept'];
        $date = Flight::request()->data->dateReal; // Date actuelle
        $previsionOuRealisation = 1; // Realisation (0)
        $validation = 0; // Valide par defaut

        // Creer un objet Valeur
        $valeur = new Valeur(0, $nomRubrique, $idType, $previsionOuRealisation, $montant, $date, $validation, $idDept);

        // Sauvegarder dans la base de donnees
        if ($valeur->insert()) {
            Flight::redirect('budget');
        } else {
            echo "Erreur lors de l'ajout de la realisation.";
        }
    }

    public function saveCRM()
    {
        // Recuperer les donnees du formulaire
        $montant = Flight::request()->data->valeur;
        $idDeptAinserer = Flight::request()->data->idDept;
        $idDept = $_SESSION['idDept'];
        $date = Flight::request()->data->dateCrm; // Date actuelle
        $action = Flight::request()->data->action;
        $crm = Flight::request()->data->idCrm;
        $labelCRM = Crm::findById($crm)->getLabel();
        $table = ($idDept == 1) ? 'Crm_vrai' : 'Crm_temp';
        $sommeCRM = Crm::getResteCRMValue($idDept, $date);
        $budget = Crm::getBudgetCrm($idDeptAinserer);
        // if ($budget > 0) {
        $insert = new Validation();
        $insert->insertCrmAction($labelCRM, $action, $idDeptAinserer, $montant, $date);
            
        // Validation::insertCrmAction($labelCRM, $action, $idDeptAinserer, $montant, $date);
            
            
        // }
        //   $stmt = $conn->prepare("INSERT INTO $table (label, action, idDept, valeur, dateCrm) VALUES (?, ?, ?, ?, ?)");
            // $stmt->execute([$labelCRM, $action, $idDeptForm, $valeur, $date]);
        if ($sommeCRM > $montant) {
            $previsionOuRealisation = 1;
            $validation = 1;

            // Sauvegarde dans la base de donnees
            for ($i=0; $i < rand(1, 10); $i++) { 
                $idProduit = rand(1,10);
                $idClient = rand(1,8);

                $today = new DateTime();
                // $minDate = (clone $today)->modify('+1 day');           // demain
                // $maxDate = (clone $today)->modify('+3 months');        // dans 3 mois

                // // Generer un timestamp aleatoire entre les deux
                // $timestampMin = $minDate->getTimestamp();
                // $timestampMax = $maxDate->getTimestamp();

                // $randomTimestamp = mt_rand($timestampMin, $timestampMax);
                $dateVente = $date;

                $quantite = rand(1,5);
                $vente = new Vente($idProduit, $idClient, $dateVente, $quantite);
                $vente->save();
            }

            // Creer un objet Valeur
            $valeur = new Valeur(0, $labelCRM, 7, $previsionOuRealisation, $montant, $date, $validation, $idDept);

            // Sauvegarder dans la base de donnees
            if ($valeur->insert()) {
                Flight::redirect('budget');
            } else {
                echo "Erreur lors de l'ajout de la realisation.";
            }
        } else {
            $previsionOuRealisation = 1;
            $validation = 0;

            // Creer un objet Valeur
            $valeur = new Valeur(0, $labelCRM, 7, $previsionOuRealisation, $montant, $date, $validation, $idDept);

            // Sauvegarder dans la base de donnees
            if ($valeur->insert()) {
                Flight::redirect('budget');
            } else {
                echo "Erreur lors de l'ajout de la realisation.";
            }
        }
    }

    public function savePrevision()
    {
        // Recuperer les donnees du formulaire
        $nomRubrique = Flight::request()->data->nature;
        $idType = Flight::request()->data->type;
        $montant = Flight::request()->data->montant;
        $idDept = $_SESSION['idDept'];
        $date = Flight::request()->data->datePrev; // Date actuelle
        $previsionOuRealisation = 0; // Realisation (0)
        $validation = 0; // Valide par defaut

        // Creer un objet Valeur
        $valeur = new Valeur(0, $nomRubrique, $idType, $previsionOuRealisation, $montant, $date, $validation, $idDept);

        // Sauvegarder dans la base de donnees
        if ($valeur->insert()) {
            Flight::redirect('budget');
        } else {
            echo "Erreur lors de l'ajout de la realisation.";
        }
    }

    public function updateAllBudgetsCrm()
    {
        $budgets = Flight::request()->data->budgets; // Tableau associatif [idBudgetCRM => montant]
        $conn = \Flight::db();
        foreach ($budgets as $idBudgetCRM => $montant) {
            $stmt = $conn->prepare("UPDATE budgetCRM SET montant = ?");
            $stmt->execute([$montant, $idBudgetCRM]);
        }
        Flight::redirect('budgetcrm-list');
    }

    public function listeBudgetsCrm()
    {
        $conn = \Flight::db();
        $budgets = $conn->query("SELECT * FROM budgetCRM")->fetchAll(\PDO::FETCH_ASSOC);
        \Flight::render('budgetcrm_list', [
            'budgets' => $budgets
        ]);
    }
}
