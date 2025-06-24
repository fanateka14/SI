<?php
namespace app\models;
use Flight;

class TicketModel {
    private $db;

    public function __construct() {
        // $this->db = $db;
        $this->db = Flight::db();

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

    public function ajoutTicketDolibarr($data)
    {
        $dolibarr = new \app\models\DolibarrModel();
        return $dolibarr->createTicket($data);
    }
}