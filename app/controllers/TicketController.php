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
                'status'       => 1, // Statut ouvert
                'tiers'        => Flight::request()->data->tiers,
                'assigne'      => Flight::request()->data->assigne,
            ];
            $model = new \app\models\TicketModel();
            $model->ajoutTicketDolibarr($data);
             $dolibarr = new \app\models\DolibarrModel();
            $tiers = $dolibarr->getTiers(); // Liste des clients/tiers Dolibarr
            $users = $dolibarr->getUsers();
            // Liste des utilisateurs Dolibarr
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
        $dolibarr = new \app\models\DolibarrModel();
        $tickets = $dolibarr->getTickets();
        $agents = $dolibarr->getAgent();
        $tiers = $dolibarr->getTiers();

        // Récupérer toutes les assignations en une seule requête
        $ticketModel = new \app\models\TicketModel();
        $assignations = $ticketModel->getAllAssignations(); // Nouvelle méthode à créer

        // Associer montantPrevu et duree à chaque ticket
        foreach ($tickets as &$ticket) {
            $id = $ticket['id'];
            if (isset($assignations[$id])) {
                $ticket['montantPrevu'] = $assignations[$id]['montantPrevu'];
                $ticket['duree'] = $assignations[$id]['duree'];
            } else {
                $ticket['montantPrevu'] = '';
                $ticket['duree'] = '';
            }
        }

        Flight::render('template', [
            'tickets' => $tickets,
            'agents' => $agents,
            'tiers' => $tiers,
            'page' => 'ticket_list'
        ]);
    }
//     public function updateTicket()
// {
//     $id = Flight::request()->data->id;
//     $montantPrevu = Flight::request()->data->montantPrevu;
//     $duree = Flight::request()->data->duree;

//     $ticketData = [
//         "status"      => (int)Flight::request()->data->statut,
//         "severity_code"  => (int)Flight::request()->data->priorite,
//         "fk_user_assign" => Flight::request()->data->agent ? (int)Flight::request()->data->agent : null
//     ];

//     $dolibarr = new \app\models\DolibarrModel();
//     $result = $dolibarr->putTicket($id, $ticketData);

//     $ticketModel = new \app\models\TicketModel();
//     $ticketModel->saveAssignationTicket($id, $montantPrevu, $duree);

//     // Optionnel : gérer le retour ou afficher un message
//     Flight::redirect('listeTicket');
// }
public function updateTicket()
{
    $id = Flight::request()->data->id;
    $montantPrevu = Flight::request()->data->montantPrevu;
    $duree = Flight::request()->data->duree;
    $dureeReelle = Flight::request()->data->dureeReelle;

    // Correction : si vide, mettre à NULL
    if ($dureeReelle === '' || $dureeReelle === null) {
        $dureeReelle = null;
    }

    $ticketData = [
        "status"        => (int)Flight::request()->data->statut,
        "severity_code" => (int)Flight::request()->data->priorite,
        "fk_user_assign"=> Flight::request()->data->agent ? (int)Flight::request()->data->agent : null
    ];

    $dolibarr = new \app\models\DolibarrModel();
    $dolibarr->putTicket($id, $ticketData);

    $ticketModel = new \app\models\TicketModel();
    $ticketModel->saveAssignationTicket($id, $montantPrevu, $duree, $dureeReelle);

    Flight::redirect('listeTicket');
}
}