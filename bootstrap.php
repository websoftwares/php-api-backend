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
use ModusCreate\Repository\{NHTSASafetyRatingsModelYearRepository, NHTSASafetyRatingsVehicleIdRepository};
use ModusCreate\Factory\NHTSAClientFactory;
use ModusCreate\Model\{NHTSASafetyRatingsModelYearModel, NHTSASafetyRatingsModelYearWithRatingModel};

/*
|--------------------------------------------------------------------------
| Create container
|--------------------------------------------------------------------------
 */
$container = new Container;
/*
|--------------------------------------------------------------------------
| Inversion of control
|--------------------------------------------------------------------------
 */
$container[NHTSASafetyRatingsModelYearModel::class] = function ($c) {
    return new NHTSASafetyRatingsModelYearModel(
        new NHTSASafetyRatingsModelYearRepository(
            NHTSAClientFactory::newInstance()
        )
    );
};
$container[NHTSASafetyRatingsModelYearWithRatingModel::class] = function ($c) {
    return new NHTSASafetyRatingsModelYearWithRatingModel(
        new NHTSASafetyRatingsVehicleIdRepository(
            NHTSAClientFactory::newInstance()
        ),
        $c->get(NHTSASafetyRatingsModelYearModel::class)
    );
};
$container[GetVehiclesAction::class] = function ($c) {
    return new GetVehiclesAction(
        $c->get(NHTSASafetyRatingsModelYearWithRatingModel::class)
    );
};
$container[PostVehiclesAction::class] = function ($c) {
    return new PostVehiclesAction(
        $c->get(NHTSASafetyRatingsModelYearModel::class)
    );
};
/*
|--------------------------------------------------------------------------
| Create app
|--------------------------------------------------------------------------
 */
$app = new App($container);
/*
|--------------------------------------------------------------------------
| Dispatch
|--------------------------------------------------------------------------
 */
$app->get('/vehicles/{modelYear}/{manufacturer}/{model}', GetVehiclesAction::class);
$app->post('/vehicles', PostVehiclesAction::class);
$app->run();
