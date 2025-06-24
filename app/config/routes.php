<?php

use app\controllers\StatController;
use app\controllers\FormController;
use app\controllers\ValeurController;
use app\controllers\ValidationController;
use app\controllers\WelcomeController;
use app\controllers\BudgetController;
use app\controllers\PdfController;

use flight\Engine;
use flight\net\Router;
use app\controllers\DepartementController;
use app\controllers\StatistiqueController;
use app\controllers\validationCRMController;
use app\controllers\TicketController;
use app\controllers\UserController;
use app\controllers\DolibarrController;
use app\controllers\LuberriController;


/** 
 * @var Router $router 
 * @var Engine $app
 */
$valeurController = new ValeurController();
// $router->post('/valeur/savePrevision', [$valeurController, 'savePrevision']);
$router->post('/saveRealisation', [$valeurController, 'saveRealisation']);
$router->post('/savePrevision', [$valeurController, 'savePrevision']);
$router->post('/saveCRM', [$valeurController, 'saveCRM']);



$dolibarrController = new DolibarrController();
$router->get('/dolibarr', [$dolibarrController, 'listProducts']);



$departementController = new DepartementController();
$router->get('/', [$departementController, 'getFormulaireLogin']);
$router->get('/deco', [$departementController, 'deconnexion']);
$router->get('/login', [$departementController, 'getFormulaireLogin']);

$router->post('/doLogin', [$departementController, 'doLogin']);

$router->post('/importer', [$valeurController, 'doImportCSV']);

$router->group('/validation', function (Router $router) {
    $validationController = new ValidationController();
    $router->get('/', [$validationController, 'getListValidation']);
    $router->get('/valider/@id:[0-9]+', [$validationController, 'valider']);
    $router->get('/refuser/@id:[0-9]+', [$validationController, 'refuser']);
});
$router->group('/validationCrm', function (Router $router) {
    $validationCRMController = new validationCRMController();
    $router->get('/', [$validationCRMController, 'getListValidationCRM']);
    $router->get('/valider/@id:[0-9]+', [$validationCRMController, 'valider']);
    $router->get('/refuser/@id:[0-9]+', [$validationCRMController, 'refuser']);
});
$BudgetController = new BudgetController();
$router->get('/budget', [$BudgetController, 'getBudget']);
$router->post('/budget', [$BudgetController, 'getBudget']);

$ticketController = new TicketController();
$router->get('/tri', [$ticketController, 'search']);

$PdfController = new PdfController();
$router->post('/export', [$PdfController, 'exportPDF']);

$router->group('/valeur', function (Router $router) {
    $valeurController = new ValeurController();
    $router->post('/savePrevision', [$valeurController, 'savePrevision']);
    $router->post('/saveRealisation', [$valeurController, 'saveRealisation']);
});


$formController = new FormController();
$router->get('/crm', [$formController, 'crm']);

$StatController = new StatistiqueController();

Flight::route('/chart', function () use ($StatController) {
    $StatController->showDashboard();
});

Flight::route('/api/ventes-par-mois', function () use ($StatController) {
    $StatController->getSalesByMonthJson();
});


$luberriController = new \app\controllers\LuberriController();

$router->get('/ajouterDiscussion', [$luberriController, 'ajouterDiscussion']);
$router->post('/ajouterDiscussion', [$luberriController, 'ajouterDiscussion']);

Flight::route('GET|POST /ajoutTicket', ['\app\controllers\TicketController', 'ajoutTicket']);
Flight::route('GET|POST /listeTicket ', ['\app\controllers\TicketController', 'listeTickets']);

Flight::route('/stats', [\app\controllers\FanaController::class, 'statistiques']);
Flight::route('/comparaison-ticket', [\app\controllers\FanaController::class, 'comparaisonDepenseTicket']);
$router->post('/updateTicket', ['\app\controllers\TicketController', 'updateTicket']);

$ticketReviewController = new \app\controllers\TicketReviewController();
$router->get('/ticketreview', [$ticketReviewController, 'listClosedTickets']);
$router->get('/ticketreview-avis/@id', [$ticketReviewController, 'showReviewForm']);
$router->post('/ticketreview-save', [$ticketReviewController, 'saveReview']);
$router->get('/ticketreview-list', [$ticketReviewController, 'listAllReviews']);
