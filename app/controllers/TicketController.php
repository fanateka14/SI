<?php
namespace app\controllers;

use app\models\TicketModel;
use Flight;

class TicketController {
    public function search() {
        $idClient = Flight::request()->query->idClient ?? null;
        $statut = Flight::request()->query->statut ?? null;
        $priorite = Flight::request()->query->priorite ?? null;
        $ticket = new TicketModel();

        $tickets = $ticket->search($idClient, $statut, $priorite);
        $clients = $ticket->getAllClients();
        // Flight::render('template', $data);

        Flight::render('template', [
            'tickets' => $tickets,
            'clients' => $clients,
            'selectedClient' => $idClient,
            'selectedStatut' => $statut,
            'selectedPriorite' => $priorite,
            'page' => 'ticket_search'
        ]);
    }

    public function ajoutTicket()
    {
        if (Flight::request()->method == 'POST') {
            $data = [
                'type_demande' => Flight::request()->data->type_demande,
                'severite'     => Flight::request()->data->severite,
                'sujet'        => Flight::request()->data->sujet,
                'message'      => Flight::request()->data->message,
                'tiers'        => Flight::request()->data->tiers,
                'assigne'      => Flight::request()->data->assigne,
            ];
            $model = new \app\models\TicketModel();
            $model->ajoutTicketDolibarr($data);
             $dolibarr = new \app\models\DolibarrModel();
            $tiers = $dolibarr->getTiers(); // Liste des clients/tiers Dolibarr
            $users = $dolibarr->getUsers(); // Liste des utilisateurs Dolibarr
            Flight::render('template', [
                'page' => 'ticket_add',
                'users' => $users,
                'tiers' => $tiers
                
            ]);
        } else {
            $dolibarr = new \app\models\DolibarrModel();
            $tiers = $dolibarr->getTiers(); // Liste des clients/tiers Dolibarr
            $users = $dolibarr->getUsers(); // Liste des utilisateurs Dolibarr
            Flight::render('template', [
                'page' => 'ticket_add',
                'users' => $users,
                'tiers' => $tiers
                
            ]);
        }
    }

     public function listeTickets() {
        $ticket = new \app\models\DolibarrModel();
        $tickets = $ticket->getTickets(); // récupère tous les tickets
        $agents = $ticket->getAgent(); 
            $tiers = $ticket->getTiers(); // Liste des clients/tiers Dolibarr

        // récupère tous les tickets
        Flight::render('template', [
            'tickets' => $tickets,
            'agents' => $agents,
            'tiers' => $tiers,
            'page' => 'ticket_list'

        ]);
    }
       public function updateTicket()
{
    $id = Flight::request()->data->id;
    $ticketData = [
        "status"      => (int)Flight::request()->data->statut,
        "severity_code"  => (int)Flight::request()->data->priorite,
        "fk_user_assign" => Flight::request()->data->agent ? (int)Flight::request()->data->agent : null
    ];

    $dolibarr = new \app\models\DolibarrModel();
    $result = $dolibarr->putTicket($id, $ticketData);

    // Optionnel : gérer le retour ou afficher un message
    Flight::redirect('listeTicket');
}
}