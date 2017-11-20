<?php
declare(strict_types=1);
namespace ModusCreate\Action;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class GetVehiclesAction
{
    private const MODEL_YEAR = 'model_year';
    private const MANUFACTURER = 'manufacturer';
    private const MODEL = 'model';

    public function __construct()
    {
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return ResponseInterface
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ) : ResponseInterface {

        // [
        //     self::MODEL_YEAR => $modelYear,
        //     self::MANUFACTURER => $manufacturer,
        //     self::MODEL => $model
        // ] = $args;


        return $response->withJson($args);
    }
}
