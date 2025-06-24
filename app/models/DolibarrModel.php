<?php
    namespace app\models;

    use Flight;
    use vendor\flightphp\core\flight\database;
    use PDO;

 
class DolibarrModel {

        private $apiUrl = 'http://localhost/dolibarr-21.0.1/dolibarr-21.0.1/htdocs/api/index.php';
    private $apiKey = '0faa8810426f7a73478550268bd2c317a56a50da';
    // private $apiUrl = 'http://localhost/dolibarr-21.0.1/htdocs/api/index.php';
    // private $apiKey = '0faa8810426f7a73478550268bd2c317a56a50da';



    //  private function makeRequest($endpoint)
    // {
    //     $url = $this->baseUrl . $endpoint;
    //     $headers = [
    //         'Accept: application/json',
    //         'DOLAPIKEY: ' . $this->apiKey
    //     ];

    //     $curl = curl_init();
    //     curl_setopt_array($curl, [
    //         CURLOPT_URL => $url,
    //         CURLOPT_RETURNTRANSFER => true,
    //         CURLOPT_HTTPHEADER => $headers,
    //     ]);

    //     $response = curl_exec($curl);

    //     if (curl_errno($curl)) {
    //         $error = curl_error($curl);
    //         curl_close($curl);
    //         return ['error' => $error];
    //     }

    //     $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    //     curl_close($curl);

    //     return [
    //         'status' => $httpCode,
    //         'data' => json_decode($response, true)
    //     ];
    // }

    public function getStatus()
    {
        return $this->makeRequest('status');
    }
     public function getAgent()
    {
        // Récupère tous les utilisateurs (agents) depuis Dolibarr
        return $this->makeRequest('users');
    }

    // public function getUsers()
    // {
    //     return $this->makeRequest('tickets');
    // }
    public function putUsers(){

    }
    public function getTickets(){
        return $this-> makerequest('tickets');
    }
    // Exemple : récupérer les produits
    public function makeRequest($args) {
          $url = $this->apiUrl .'/'. $args;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'DOLAPIKEY: ' . $this->apiKey,
            'Accept: application/json',
        ]);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }
    public function putRequest($endpoint, $data)
    {
        $url = $this->apiUrl . '/' . $endpoint;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT"); // Définir la méthode PUT
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'DOLAPIKEY: ' . $this->apiKey,
            'Content-Type: application/json',
            'Accept: application/json'
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data)); // Envoyer les données JSON

        $response = curl_exec($ch);

        curl_close($ch);

        return json_decode($response, true);
    }
     public function deleteRequest($endpoint)
    {
        $url = $this->apiUrl . '/' . $endpoint;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE"); // Définir la méthode PUT
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'DOLAPIKEY: ' . $this->apiKey,
            'Content-Type: application/json',
            'Accept: application/json'
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data)); // Envoyer les données JSON

        $response = curl_exec($ch);

        curl_close($ch);

        return json_decode($response, true);
    }
    public function putUser($id ,$data)
    {
        return $this->putRequest('users/'.$id,$data);
    }
    public function deleteUser($id)
    {
        return $this->deleteRequest('users/'.$id);
    }
    

    public function createUser($userData) {
        $userData = [
    "login"     => "iout",
    "lastname"  => "Dupont",
    "firstname" => "Jean",
    "email"     => "ioty@gmal.com",
    "admin"     => 0,
    "employee"  => 2,
    "statut"    => 1,
    "gender"    => "man"
];

        $url = $this->apiUrl . '/users';

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'DOLAPIKEY: ' . $this->apiKey,
            'Content-Type: application/json',
            'Accept: application/json',
        ]);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true); // méthode POST
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($userData)); // les données à envoyer

        $response = curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return [
            'status' => $statusCode,
            'response' => json_decode($response, true),
        ];
}

public function createTicket($data)
{
    $url = $this->apiUrl . '/tickets';
    $payload = [
        'type_code'   => $data['type_demande'],
        'severity_code' => $data['severite'],
        'subject'     => $data['sujet'],
        'message'     => $data['message'],
        'fk_soc'      => $data['tiers'],
        'fk_user_assign' => $data['assigne'],
    ];
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'DOLAPIKEY: ' . $this->apiKey,
        'Content-Type: application/json',
        'Accept: application/json',
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response, true);
}

public function getTiers()
{
    return $this->makeRequest('thirdparties');
}

public function getUsers()
{
    return $this->makeRequest('users');
}
public function updateTicket($id, $data)
{
    return $this->makeRequest('tickets/' . $id, 'PUT', $data);
}
public function putTicket($id, $data)
{
    return $this->putRequest('tickets/' . $id, $data);
}
}
?>
