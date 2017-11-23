<?php
declare (strict_types = 1);
namespace ModusCreate\Action;

use Psr\Http\Message\{ServerRequestInterface, ResponseInterface};
use ModusCreate\Model\ModelInterface;

class GetVehiclesAction
{
    private const MODEL_YEAR = 'model_year';
    private const MANUFACTURER = 'manufacturer';
    private const MODEL = 'model';

    /**
     * @var ModelInterface
     */
    private $model;

    public function __construct(ModelInterface $model)
    {
        $this->model = $model;
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
        // We should of course in a real life situation add validation, sanitizing,
        // etc here before sending it to the next layer.
        $result = $this->model->find($args);
        return $response->withJson($result);
    }
}
