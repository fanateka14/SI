<?php
    namespace app\models;

use app\Models\AffichageBudjetPeriode;
use DateTime;
    use DateInterval;
    use DatePeriod;
    use Flight;

    class Solde
    {
        private $conn;

        public function __construct()
        {
            $this->conn = Flight::db();
        }

        public function getSoldeInitial($idDept) {
            $sql = "SELECT * FROM soldeInitial WHERE idDept = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$idDept]);
            $solde = $stmt->fetch(\PDO::FETCH_ASSOC);
        
            // Verifier si un solde a ete trouve, sinon retourner 0
            if ($solde) {
                return $solde;
            } else {
                return null;  // Valeur par defaut si aucun solde n'est trouve
            }
        }
        public function getSolde($idDept, $dateAVoir) {
            $dateDebut = $this->getSoldeInitial($idDept)['dateInsertion'];
            $soldeInitial = $this->getSoldeInitial($idDept)['montant'];
        
            // Convertir en objets DateTime
            $start = new DateTime($dateDebut);
            $end = new DateTime($dateAVoir); // Ne pas modifier ici
        
            // Creer l'intervalle d'un mois
            $interval = new DateInterval('P1M');
        
            // Creer la periode (exclusif de la date de fin)
            $period = new DatePeriod($start, $interval, $end);
        
            // Parcourir chaque mois
            foreach ($period as $date) {
                $valeur = AffichageBudjetPeriode::getRealisationTotalByMonthYear($idDept, $date->format('m'), $date->format('Y'));
                $recette = $valeur['totalRecettes'];
                $depense = $valeur['totalDepenses'];
                $solde = $recette - $depense;
                $soldeInitial += $solde;
            }
        
            return $soldeInitial;
        }
    }        
?>