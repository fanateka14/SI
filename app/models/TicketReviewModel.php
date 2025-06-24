<?php
namespace app\models;

use PDO;

class TicketReviewModel {
    private $db;
    public function __construct() {
        $this->db = new PDO('mysql:host=localhost;dbname=gestion;charset=utf8', 'root', '');
    }
    public function getAllReviews() {
        // On ne peut pas faire de jointure SQL directe avec Dolibarr (API), donc on récupère les tickets via l'API
        $sql = "SELECT * FROM ticket_review ORDER BY date_avis DESC";
        $stmt = $this->db->query($sql);
        $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // Récupérer les sujets des tickets via l'API Dolibarr
        $dolibarr = new \app\models\DolibarrModel();
        $tickets = $dolibarr->getTickets();
        $subjects = [];
        if (is_array($tickets)) {
            foreach ($tickets as $t) {
                if (isset($t['id'])) {
                    $subjects[$t['id']] = $t['subject'] ?? '';
                }
            }
        }
        // Ajouter le sujet à chaque review
        foreach ($reviews as &$r) {
            $r['subject'] = isset($subjects[$r['id_ticket']]) ? $subjects[$r['id_ticket']] : '';
        }
        return $reviews;
    }
    public function getReviewsByTicket($id_ticket) {
        $sql = "SELECT * FROM ticket_review WHERE id_ticket = ? ORDER BY date_avis DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_ticket]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function addReview($id_ticket, $commentaire, $nb_etoile) {
        $sql = "INSERT INTO ticket_review (id_ticket, commentaire, nb_etoile) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id_ticket, $commentaire, $nb_etoile]);
    }
}
