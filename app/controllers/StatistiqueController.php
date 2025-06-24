<?php

namespace app\controllers;

use app\models\Statistique;
use Flight;

class StatistiqueController {

    public static function showDashboard() {
        $statistiqueModel = new Statistique(Flight::db());
        // Recuperation de l'annee (parametre optionnel)
        $year = Flight::request()->query->year ?? date('Y');
        // $year = 2025;
        // Recuperer les donnees de vente par mois
        $salesByMonth = $statistiqueModel->getSalesByMonth($year);
        
        // Preparer les donnees pour le graphique
        $months = [];
        $sales = [];
        
        foreach ($salesByMonth as $data) {
            // Convertir le numero du mois en nom du mois en français
            $monthNum = $data['mois'];
            $dateObj = \DateTime::createFromFormat('!m', $monthNum);
            $monthName = $dateObj->format('F'); // Nom du mois en anglais
            
            // Traduction des mois en français
            $frenchMonths = [
                'January' => 'Jan.',
                'February' => 'Fev.',
                'March' => 'Mars',
                'April' => 'Avr.',
                'May' => 'Mai',
                'June' => 'Juin',
                'July' => 'Juil.',
                'August' => 'Août',
                'September' => 'Sept.',
                'October' => 'Oct.',
                'November' => 'Nov.',
                'December' => 'Dec.'
            ];
            
            $months[] = $frenchMonths[$monthName];
            $sales[] = $data['total_ventes'];
        }
        
        // Recuperer les autres donnees statistiques
        $bestProducts = $statistiqueModel->getBestProduct();
        $topCustomers = $statistiqueModel->getTopCustomers();
        $chiffreAffaire = $statistiqueModel->getChiffreAffaire($year);
        
        // Passer les donnees a la vue
        Flight::render('template', [
            'page' => 'chart',
            'months' => json_encode($months),
            'sales' => json_encode($sales),
            'year' => $year,
            'bestProducts' => $bestProducts,
            'topCustomers' => $topCustomers,
            'chiffreAffaire' => $chiffreAffaire
        ]);
    }
    
    public function getSalesByMonthJson() {
        $statistiqueModel = new Statistique(Flight::db());
        $year = Flight::request()->query->year ?? date('Y');
        $salesByMonth = $statistiqueModel->getSalesByMonth($year);
        
        Flight::json($salesByMonth);
    }
}