<?php
namespace app\Models;

use Flight;

class ValidationCRM {

    private $db;

    public function __construct() {
        $this->db = Flight::db();
    }

    public function setValidationValider($id) : bool {
        try {
            $stmt = $this->db->prepare("SELECT * FROM crm_temp WHERE idCrm = ?");
            $stmt->execute([$id]);
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);

            if ($row) {
                // Inserer dans crm_vrai
                $insert = $this->db->prepare("INSERT INTO crm_vrai (label, action, idDept, valeur, dateCrm) VALUES (?, ?, ?, ?, ?)");
                $insert->execute([
                    $row['label'],
                    $row['action'],
                    $row['idDept'],
                    $row['valeur'],
                    $row['dateCrm']
                ]);
                // Supprimer de crm_temp
                $delete = $this->db->prepare("DELETE FROM crm_temp WHERE idCrm = ?");
                $delete->execute([$id]);
            }
        } catch (\PDOException $e) {
            error_log("Erreur SQL : " . $e->getMessage());
            return false;
        }
        return true;
    }
    public function insertCrmAction($label, $action, $idDept, $valeur, $dateCrm) {
        $table = ($idDept == 1) ? 'Crm' : 'Crm_temp';
        $sql = "INSERT INTO $table (label, action, idDept, valeur, dateCrm) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$label, $action, $idDept, $valeur, $dateCrm]);
    }
    public function setValidationRefuser($id) : bool {
        try {
            $query = $this->db->prepare("UPDATE Valeur SET validation = 2 WHERE idValeur = $id");
            if ($query->execute()) {
                return $query->rowCount() > 0; // Verifie si une ligne a ete mise a jour
            }
            return false;
        } catch (\PDOException $e) {
            error_log("Erreur SQL : " . $e->getMessage());
            return false;
        }
    }

    public function getAllValidationCRM() {
        $query = "
            SELECT 
                idCrm, 
                label, 
                action, 
                idDept, 
                valeur, 
                dateCrm
             
            FROM crm_temp  ;
           
        ";
        $result = $this->db->query($query);
        return $result->fetchAll(\PDO::FETCH_ASSOC);
    }
}
