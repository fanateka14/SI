<?php

namespace app\controllers;

use app\models\FanaModel;
use Flight;

class FanaController
{
    // Affiche la page de statistiques
    public function statistiques()
    {
        $periode = null;
        if (!empty($_GET['start']) && !empty($_GET['end'])) {
            $periode = [
                'start' => $_GET['start'],
                'end' => $_GET['end']
            ];
        }
        $model = new FanaModel();
        $stats = $model->getStatistiques($periode);
        Flight::render('template', [
            'page' => 'fana_stats',
            'stats' => $stats
        ]);
    }

    public function comparaisonDepenseTicket()
    {
        $model = new FanaModel();
        $stats = $model->getComparaisonDepenseTicket();
        Flight::render('template', [
            'page' => 'fana_comparaison_ticket',
            'stats' => $stats
        ]);
    }
}
