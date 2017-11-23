<?php
/*
|--------------------------------------------------------------------------
| Syrict types
|--------------------------------------------------------------------------
 */
declare (strict_types = 1);
/*
|--------------------------------------------------------------------------
| Start session
|--------------------------------------------------------------------------
 */
session_start();
/*
|--------------------------------------------------------------------------
| Error reporting enabled default remove for production
|--------------------------------------------------------------------------
 */
error_reporting(E_ALL);
ini_set('display_errors', '1');
/*
|--------------------------------------------------------------------------
| Autoload classes
|--------------------------------------------------------------------------
 */
include 'vendor/autoload.php';
/*
|--------------------------------------------------------------------------
| Imports
|--------------------------------------------------------------------------
 */
use Slim\Container;
use Slim\App;
use ModusCreate\Action\{GetVehiclesAction, PostVehiclesAction};
use ModusCreate\Repository\NHTSASafetyRatingsModelYearRepository;
use ModusCreate\Factory\NHTSAClientFactory;
use ModusCreate\Model\NHTSASafetyRatingsModelYearModel;

/*
|--------------------------------------------------------------------------
| Create container
|--------------------------------------------------------------------------
 */
$container = new Container;
/*
|--------------------------------------------------------------------------
| Create app
|--------------------------------------------------------------------------
 */
$app = new App($container);
/*
|--------------------------------------------------------------------------
| Inversion of control
|--------------------------------------------------------------------------
 */
$container = $app->getContainer();
$container[NHTSASafetyRatingsModelYearModel::class] = function ($c) {
    return new NHTSASafetyRatingsModelYearModel(
        new NHTSASafetyRatingsModelYearRepository(
            NHTSAClientFactory::newInstance()
        )
    );
};
$container[GetVehiclesAction::class] = function ($c) {
    return new GetVehiclesAction(
        $c->get(NHTSASafetyRatingsModelYearModel::class)
    );
};

$container[PostVehiclesAction::class] = function ($c) {
    return new PostVehiclesAction(
        $c->get(NHTSASafetyRatingsModelYearModel::class)
    );
};
/*
|--------------------------------------------------------------------------
| Dispatch
|--------------------------------------------------------------------------
 */
$app->get('/vehicles/{modelYear}/{manufacturer}/{model}', GetVehiclesAction::class);
$app->post('/vehicles', PostVehiclesAction::class);
$app->run();
