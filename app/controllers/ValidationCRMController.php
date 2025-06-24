<?php

namespace app\controllers;

use app\models\ValidationCRM;
use Flight;

class ValidationCRMController {
	public function getListValidationCRM() {

        $validation = new ValidationCRM();
        $data = [
            'page' => 'validationCRM',
            'validations' => $validation->getAllValidationCRM()
        ];
        Flight::render('template', $data);
    }

   
    public function valider($id)  {
        $validation = new ValidationCRM();
        $success = $validation->setValidationValider($id);

        if ($success) {
            Flight::redirect('/validation');
        } else {
            Flight::halt(500, 'Erreur lors de la mise a jour de la validation. Verifiez les logs pour plus de details.');
        }
    }

    public function refuser($id) {
        $db = Flight::db();
        $delete = $db->prepare("DELETE FROM crm_temp WHERE idCrm = ?");
        $delete->execute([$id]);
        Flight::redirect('/validationCrm');
    }
 
}