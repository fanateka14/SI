<?php
namespace app\controllers;

use app\models\LuberriModel;
use Flight;

class LuberriController {
    protected $model;

    public function __construct() {
        
    }

    public function getDiscussions($idTier) {
        $discussions = $this->model->getDiscussionsByTier($idTier);
        Flight::json($discussions);
    }

    public function creerDiscussion() {
        $idTier = Flight::request()->data->idTier;
        $message = Flight::request()->data->message;
        $reponse = Flight::request()->data->reponse ?? null;
        $success = $this->model->creerDiscussion($idTier, $message, $reponse);
        Flight::json(['success' => $success]);
    }

    public function ajouterDiscussion() {
        $dolibarr = new \app\models\DolibarrModel();
        $tiers = $dolibarr->getTiers();
        $model = new \app\models\LuberriModel();

        $idTier = null;
        if (Flight::request()->method == 'POST') {
            // Réponse à une discussion existante
            if (isset(Flight::request()->data->submit_reponse)) {
                $discussionId = Flight::request()->data->discussion_id;
                $reponse = Flight::request()->data->reponse;
                if ($discussionId && $reponse) {
                    $model->ajouterReponse($discussionId, $reponse);
                    Flight::redirect('ajouterDiscussion');
                    return;
                }
            } else {
                // Ajout d'une nouvelle discussion
                $idTier = Flight::request()->data->idTier;
                $message = Flight::request()->data->message;
                $reponse = Flight::request()->data->reponse ?? null;

                if ($idTier && $message) {
                    $model->creerDiscussion($idTier, $message, $reponse);
                    Flight::redirect('ajouterDiscussion');
                    return;
                }
            }
        }

        // Récupération des filtres de date
        $dateDebut = Flight::request()->query->dateDebut ?? null;
        $dateFin = Flight::request()->query->dateFin ?? null;

        if (!$idTier && !empty($tiers)) {
            $idTier = $tiers[0]['id'];
        }
        $discussions = $model->getDiscussionsByTierAndDate($idTier, $dateDebut, $dateFin);

        $tiersById = [];
        foreach ($tiers as $tier) {
            $tiersById[$tier['id']] = $tier['name'] ?? $tier['nom'] ?? $tier['label'] ?? 'Tiers';
        }

        Flight::render('template', [
            'page' => 'discussion',
            'tiers' => $tiers,
            'discussions' => $discussions,
            'tiersById' => $tiersById
        ]);
    }
}