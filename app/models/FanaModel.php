<?php

namespace app\models;

use PDO;

class FanaModel
{
    private $db;
    private $dolibarrApiUrl = 'http://localhost/dolibarr-21.0.1/dolibarr-21.0.1/htdocs/api/index.php';
    private $dolibarrApiKey = '0faa8810426f7a73478550268bd2c317a56a50da';

    public function __construct()
    {
        $this->db = new PDO('mysql:host=localhost;dbname=gestion;charset=utf8', 'root', '');
    }

    // Récupère les stats coût généré par ticket, client, période, comparé au budget
    public function getStatistiques($periode = null)
    {
        $tickets = $this->getTicketsDolibarr($periode);
        var_dump($tickets); // DEBUG : affiche les tickets récupérés
        $stats = [];
        foreach ($tickets as $t) {
            if (!isset($t['id'])) continue; // Utilise 'id' au lieu de 'rowid'
            // Conversion du timestamp en date pour le filtre (si besoin)
            if (isset($t['datec']) && is_numeric($t['datec'])) {
                $t['datec_str'] = date('Y-m-d', $t['datec']);
            } else {
                $t['datec_str'] = '';
            }
            $client = $t['fk_soc'];
            if (!isset($stats[$client])) {
                $stats[$client] = [
                    'cout_genere' => 0,
                    'nb_tickets' => 0,
                    'tickets' => [],
                    'budget' => $this->getBudgetClient($client)
                ];
            }
            // On va chercher l'assignation pour ce ticket
            $assign = $this->getAssignationTicket($t['id']);
            if ($assign) {
                $cout = $assign['montantPrevu'] * $assign['duree'];
                $stats[$client]['cout_genere'] += $cout;
                $t['montantPrevu'] = $assign['montantPrevu'];
                $t['duree'] = $assign['duree'];
            } else {
                $t['montantPrevu'] = 0;
                $t['duree'] = 0;
            }
            $stats[$client]['nb_tickets']++;
            $stats[$client]['tickets'][] = $t;
        }
        return $stats;
    }

    // Récupère les tickets depuis Dolibarr via l'API
    public function getTicketsDolibarr($periode = null)
    {
        $endpoint = 'tickets';
        $tickets = $this->makeRequest($endpoint);
        // Conversion du timestamp en date pour chaque ticket
        if (is_array($tickets)) {
            foreach ($tickets as &$t) {
                if (isset($t['datec']) && is_numeric($t['datec'])) {
                    $t['datec_str'] = date('Y-m-d', $t['datec']);
                } else {
                    $t['datec_str'] = '';
                }
            }
        }
        if ($periode && is_array($tickets)) {
            $tickets = array_filter($tickets, function ($t) use ($periode) {
                return (
                    isset($t['datec_str']) &&
                    $t['datec_str'] >= $periode['start'] &&
                    $t['datec_str'] <= $periode['end']
                );
            });
        }
        return is_array($tickets) ? $tickets : [];
    }

    // Récupère l'assignation d'un ticket
    public function getAssignationTicket($idTicket)
    {
        $sql = "SELECT * FROM assignation_ticket WHERE idTicket = :idTicket LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':idTicket', $idTicket);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Récupère le budget d'un département/client depuis budgetcrm
    public function getBudgetClient($idDept)
    {
        $sql = "SELECT SUM(montant) as budget FROM budgetcrm WHERE idDept = :idDept";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':idDept', $idDept);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['budget'] : 0;
    }

    // Appel API Dolibarr (comme dans DolibarrModel)
    public function makeRequest($args)
    {
        $url = $this->dolibarrApiUrl . '/' . $args;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'DOLAPIKEY: ' . $this->dolibarrApiKey,
            'Accept: application/json',
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        echo '<pre style="background:#fff;color:#000;">API RESPONSE:<br>';
        var_dump($response);
        echo '</pre>';
        curl_close($ch);
        return json_decode($response, true);
    }
}
