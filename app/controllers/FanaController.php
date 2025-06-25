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

    public function exportStatsPdf()
    {
        $fanaModel = new \app\models\FanaModel();
        $periode = null;
        if (!empty($_GET['start']) && !empty($_GET['end'])) {
            $periode = [$_GET['start'], $_GET['end']];
        }
        $stats = $fanaModel->getStatistiques($periode);

        require_once __DIR__ . '/../views/fpdf.php';
        $pdf = new \FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Statistiques Coet Genere par Client', 0, 1, 'C');
        $pdf->Ln(5);

        // En-tête du tableau
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(60, 10, 'Client', 1);
        $pdf->Cell(30, 10, 'Nb Tickets', 1);
        $pdf->Cell(35, 10, 'Cout prev.', 1);
        $pdf->Cell(35, 10, 'Cout reel', 1);
        // $pdf->Cell(30, 10, 'Budget', 1);
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 12);
        foreach ($stats as $data) {
            $pdf->Cell(60, 10, $data['client_name'], 1);
            $pdf->Cell(30, 10, $data['nb_tickets'], 1, 0, 'C');
            $pdf->Cell(35, 10, number_format($data['cout_genere'], 2, ',', ' '), 1, 0, 'R');
            $pdf->Cell(35, 10, number_format($data['cout_genere_reel'], 2, ',', ' '), 1, 0, 'R');
            // $pdf->Cell(30, 10, number_format($data['budget'], 2, ',', ' '), 1, 0, 'R');
            $pdf->Ln();
        }

        $pdf->Output('D', 'statistiques_clients.pdf');
        exit;
    }

    public function exportComparaisonTicketPdf()
    {
        $fanaModel = new \app\models\FanaModel();
        $stats = $fanaModel->getComparaisonDepenseTicket();

        require_once __DIR__ . '/../views/fpdf.php';
        $pdf = new \FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Comparaison Depense vs Budget par Ticket', 0, 1, 'C');
        $pdf->Ln(5);

        // En-tête du tableau
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(25, 10, 'Ticket', 1);
        $pdf->Cell(40, 10, 'Client', 1);
        $pdf->Cell(50, 10, 'Sujet', 1);
        $pdf->Cell(30, 10, 'Depense reelle', 1);
        $pdf->Cell(30, 10, 'Budget', 1);
        $pdf->Cell(30, 10, 'Ecart reel', 1);
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 12);
        foreach ($stats as $row) {
            $depenseReelle = $row['montantPrevu'] * $row['dureeReel'];
            $budget = $row['montantPrevu'] * $row['duree'];
            $ecart = $depenseReelle - $budget;
            $pdf->Cell(25, 10, $row['ticket_id'], 1);
            $pdf->Cell(40, 10, $row['client'], 1);
            $pdf->Cell(50, 10, mb_substr($row['sujet'], 0, 30), 1); // Sujet tronqué si trop long
            $pdf->Cell(30, 10, number_format($depenseReelle, 2, ',', ' '), 1, 0, 'R');
            $pdf->Cell(30, 10, number_format($budget, 2, ',', ' '), 1, 0, 'R');
            $pdf->Cell(30, 10, number_format($ecart, 2, ',', ' '), 1, 0, 'R');
            $pdf->Ln();
        }

        $pdf->Output('D', 'comparaison_ticket.pdf');
        exit;
    }
}
