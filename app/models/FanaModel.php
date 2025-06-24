<?php

namespace app\models;

use PDO;

class FanaModel
{
    private $db;
    // private $dolibarrApiUrl = 'http://localhost/dolibarr-21.0.1/dolibarr-21.0.1/htdocs/api/index.php';
    // private $dolibarrApiKey = '0faa8810426f7a73478550268bd2c317a56a50da';
    private $dolibarrApiUrl = 'http://localhost/dolibarr-21.0.1/htdocs/api/index.php';
    private $dolibarrApiKey = '77db0a73baace16da2e826df8c9c3f630c3f7d40';

    public function __construct()
    {
        $this->db = new PDO('mysql:host=localhost;dbname=gestion;charset=utf8', 'root', '');
    }

    // Récupère la liste des clients (tiers) depuis Dolibarr
    public function getClientsDolibarr()
    {
        $dolibarr = new \app\models\DolibarrModel();
        $clients = $dolibarr->getTiers();
        $map = [];
        if (is_array($clients)) {
            foreach ($clients as $c) {
                if (isset($c['id']) && isset($c['name'])) {
                    $map[$c['id']] = $c['name'];
                }
            }
        }
        return $map;
    }

    // Récupère les stats coût généré par ticket, client, période, comparé au budget
    public function getStatistiques($periode = null)
    {
        $tickets = $this->getTicketsDolibarr($periode);
        $clientsMap = $this->getClientsDolibarr();
        $stats = [];
        foreach ($tickets as $t) {
            if (!isset($t['id'])) continue; // Utilise 'id' au lieu de 'rowid'
            // Conversion du timestamp en date pour le filtre (si besoin)
            if (isset($t['datec']) && is_numeric($t['datec'])) {
                $t['datec_str'] = date('Y-m-d', $t['datec']);
            } else {
                $t['datec_str'] = '';
            }
            $clientId = $t['fk_soc'];
            $clientName = isset($clientsMap[$clientId]) ? $clientsMap[$clientId] : $clientId;
            if (!isset($stats[$clientId])) {
                $stats[$clientId] = [
                    'client_name' => $clientName,
                    'cout_genere' => 0,
                    'cout_genere_reel' => 0,
                    'nb_tickets' => 0,
                    'tickets' => [],
                    'budget' => $this->getBudgetClient($clientId)
                ];
            }
            // On va chercher l'assignation pour ce ticket
            $assign = $this->getAssignationTicket($t['id']);
            if ($assign) {
                $cout = $assign['montantPrevu'] * $assign['duree'];
                $coutReel = $assign['montantPrevu'] * $assign['dureeReel'];
                $stats[$clientId]['cout_genere'] += $cout;
                $stats[$clientId]['cout_genere_reel'] += $coutReel;
                $t['montantPrevu'] = $assign['montantPrevu'];
                $t['duree'] = $assign['duree'];
                $t['dureeReel'] = $assign['dureeReel'];
            } else {
                $t['montantPrevu'] = 0;
                $t['duree'] = 0;
                $t['dureeReel'] = 0;
            }
            $stats[$clientId]['nb_tickets']++;
            $stats[$clientId]['tickets'][] = $t;
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
        // Suppression des logs de debug (echo/var_dump)
        curl_close($ch);
        return json_decode($response, true);
    }

    // Comparaison dépense par rapport au budget ticket (dépense réelle = montantPrevu * dureeReel)
    public function getComparaisonDepenseTicket()
    {
        $tickets = $this->getTicketsDolibarr();
        $clientsMap = $this->getClientsDolibarr();
        $result = [];
        foreach ($tickets as $t) {
            if (!isset($t['id'])) continue;
            $assign = $this->getAssignationTicket($t['id']);
            $montantPrevu = $assign ? $assign['montantPrevu'] : 0;
            $duree = $assign ? $assign['duree'] : 0;
            $dureeReel = $assign ? $assign['dureeReel'] : 0;
            $depense = $montantPrevu * $dureeReel;
            $budget = $montantPrevu * $duree;
            $clientId = $t['fk_soc'];
            $clientName = isset($clientsMap[$clientId]) ? $clientsMap[$clientId] : $clientId;
            $result[] = [
                'ticket_id' => $t['id'],
                'client' => $clientName,
                'sujet' => $t['subject'] ?? '',
                'montantPrevu' => $montantPrevu,
                'duree' => $duree,
                'dureeReel' => $dureeReel,
                'depense' => $depense,
                'budget' => $budget,
                'ecart' => abs($depense - $budget)
            ];
        }
        return $result;
    }
}
