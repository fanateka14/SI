<?php
namespace app\models;
use Flight;

class TicketModel {
    private $db;

    public function __construct() {
        // $this->db = $db;
        $this->db = \Flight::db();

    }
    public  function search($idClient = null, $statut = null, $priorite = null) {
        // $db = Flight::db();
        $sql = "SELECT t.idTicket, c.nomClient, h.instruction, h.date, h.priorite, h.statut
                FROM Ticket t
                JOIN client c ON t.idClient = c.idClient
                JOIN (
                    SELECT idTicket, instruction, date, priorite, statut
                    FROM Historique_Ticket
                    WHERE idHistorique IN (
                        SELECT MAX(idHistorique) FROM Historique_Ticket GROUP BY idTicket
                    )
                ) h ON t.idTicket = h.idTicket
                WHERE 1=1";
        $params = [];

        if ($idClient) {
            $sql .= " AND t.idClient = ?";
            $params[] = $idClient;
        }
        if ($statut) {
            $sql .= " AND h.statut = ?";
            $params[] = $statut;
        }
        if ($priorite) {
            $sql .= " AND h.priorite = ?";
            $params[] = $priorite;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public  function getAllClients() {
        // $db = Flight::db();
        $stmt = $this->db->query("SELECT idClient, nomClient FROM client");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // public function ajoutTicketDolibarr($data)
    // {
    //     $dolibarr = new \app\models\DolibarrModel();
    //     return $dolibarr->createTicket($data);
    // }
    public function ajoutTicketDolibarr($data)
{
    $dolibarr = new \app\models\DolibarrModel();
    $result = $dolibarr->createTicket($data);
    // $idTicket = $result['id'];
    // $this->db->query("insert into assignation_ticket (idTicket, montantPrevu, duree) VALUES ($idTicket, 0, 0)");
    // Si la création du ticket a réussi et qu'on a l'id du ticket
    // if (isset($result['id'])) {
    //     $idTicket = $result['id'];
    //     try {
    //         $stmt = $this->db->prepare("INSERT INTO assignation_ticket (idTicket, montantPrevu, duree) VALUES (?, 0, 0)");
    //         $stmt->execute([12345]);
    //     } catch (\PDOException $e) {
    //         echo "Erreur SQL : " . $e->getMessage();
    //     }
    // }

    return $result;
}
// public function saveAssignationTicket($idTicket, $montantPrevu, $duree)
// {
//     // Vérifie si une assignation existe déjà
//     $stmt = $this->db->prepare("SELECT idAsignation FROM assignation_ticket WHERE idTicket = ?");
//     $stmt->execute([$idTicket]);
//     if ($stmt->fetch()) {
//         // Mise à jour
//         $stmt = $this->db->prepare("UPDATE assignation_ticket SET montantPrevu = ?, duree = ? WHERE idTicket = ?");
//         $stmt->execute([$montantPrevu, $duree, $idTicket]);
//     } else {
//         // Insertion
//         $stmt = $this->db->prepare("INSERT INTO assignation_ticket (idTicket, montantPrevu, duree) VALUES (?, ?, ?)");
//         $stmt->execute([$idTicket, $montantPrevu, $duree]);
//     }
// }
public function saveAssignationTicket($idTicket, $montantPrevu, $duree, $dureeReelle = null)
{
    $stmt = $this->db->prepare("SELECT idAsignation FROM assignation_ticket WHERE idTicket = ?");
    $stmt->execute([$idTicket]);
    if ($stmt->fetch()) {
        // Mise à jour
        if ($dureeReelle !== null && $dureeReelle !== '') {
            $stmt = $this->db->prepare("UPDATE assignation_ticket SET montantPrevu = ?, duree = ?, dureeReel = ? WHERE idTicket = ?");
            $stmt->execute([$montantPrevu, $duree, $dureeReelle, $idTicket]);
        } else {
            $stmt = $this->db->prepare("UPDATE assignation_ticket SET montantPrevu = ?, duree = ? WHERE idTicket = ?");
            $stmt->execute([$montantPrevu, $duree, $idTicket]);
        }
    } else {
        // Insertion
        $stmt = $this->db->prepare("INSERT INTO assignation_ticket (idTicket, montantPrevu, duree, dureeReel) VALUES (?, ?, ?, ?)");
        $stmt->execute([$idTicket, $montantPrevu, $duree, $dureeReelle]);
    }
}
public function getAllAssignations()
{
    $stmt = $this->db->query("SELECT idTicket, montantPrevu, duree FROM assignation_ticket");
    $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    $assignations = [];
    foreach ($rows as $row) {
        $assignations[$row['idTicket']] = $row;
    }
    return $assignations;
}
    
}