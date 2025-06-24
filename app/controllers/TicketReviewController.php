<?php

namespace app\controllers;

use app\models\TicketReviewModel;
use app\models\DolibarrModel;
use Flight;

class TicketReviewController
{
    public function listClosedTickets()
    {
        $dolibarr = new DolibarrModel();
        $tickets = $dolibarr->getTickets();
        $closed = array_filter($tickets, function ($t) {
            return isset($t['fk_statut']) && ($t['fk_statut'] == 3 || $t['fk_statut'] == '3');
        });
        Flight::render('template', [
            'page' => 'ticket_review_list',
            'tickets' => $closed
        ]);
    }
    public function showReviewForm($id)
    {
        $model = new TicketReviewModel();
        $reviews = $model->getReviewsByTicket($id);
        Flight::render('template', [
            'page' => 'ticket_review_form',
            'id_ticket' => $id,
            'reviews' => $reviews
        ]);
    }
    public function saveReview()
    {
        $id_ticket = Flight::request()->data->id_ticket;
        $commentaire = Flight::request()->data->commentaire;
        $nb_etoile = Flight::request()->data->nb_etoile;
        $model = new TicketReviewModel();
        $model->addReview($id_ticket, $commentaire, $nb_etoile);
        // Après enregistrement, rediriger vers la page d'accueil (template.php)
        Flight::redirect('/ticketreview-list');
    }
    public function listAllReviews()
    {
        $model = new TicketReviewModel();
        $reviews = $model->getAllReviews();
        Flight::render('template', [
            'page' => 'ticket_review_all',
            'reviews' => $reviews
        ]);
    }
}
