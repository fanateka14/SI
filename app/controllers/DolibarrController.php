<?php
namespace app\controllers;

use Flight;

use app\models\DolibarrModel;

class DolibarrController {
    private $model;

    public function __construct() {
        $this->model = new DolibarrModel();
    }

    public function listProducts() {
        $products = $this->model->getUsers();
        Flight::render('products_view.php', ['products' => $products]);
    }

    public function addUser() {
    $userData = Flight::request()->data->getData(); // récupère les données JSON envoyées

    $result = $this->model->createUser($userData);
    Flight::json($result);
    }
  public function putUser()
    {
        $id = 3;
        $userData = [
            "login" => "lu00000"    
        ];
        $result = $this->model->putUser($id,$userData);
    }
    public function deleteUser()
    {
        $id = 3;
        $userData = [
            "login" => "lu00000"    
        ];
        $result = $this->model->deleteUser($id);
    }
    

}
?>
