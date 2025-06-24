<?php
namespace app\models;

use PDO;

use Flight;
class LuberriModel {
    protected $db;

    public function __construct() {
        $this->db = Flight::db();
    }

    // Exemple de méthode pour récupérer les discussions
    public function getDiscussionsByTier($idTier) {
        $stmt = $this->db->prepare("SELECT * FROM discussion WHERE idTier = ? ORDER BY dateHeure DESC");
        $stmt->execute([$idTier]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Exemple de méthode pour créer une discussion
    public function creerDiscussion($idTier, $message, $reponse = null) {
        $stmt = $this->db->prepare("INSERT INTO discussion (idTier, message, reponse) VALUES (?, ?, ?)");
        return $stmt->execute([$idTier, $message, $reponse]);
    }

    public function ajouterDiscussion() {
        $dolibarr = new \app\models\DolibarrModel();
        $tiers = $dolibarr->getTiers();
        $model = new LuberriModel();
        $discussions = $model->getDiscussionsByTier(1); // À adapter selon besoin
        Flight::render('discussion', [
            'discussions' => $discussions,
            'tiers' => $tiers
        ]);
    }
    public function ajouterReponse($discussionId, $reponse) {
        $db = \Flight::db();
        $stmt = $db->prepare("UPDATE discussion SET reponse = ? WHERE id = ?");
        return $stmt->execute([$reponse, $discussionId]);
    }
}