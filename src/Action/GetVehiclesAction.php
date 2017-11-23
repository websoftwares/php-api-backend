<?php
declare (strict_types = 1);
namespace ModusCreate\Action;

use Psr\Http\Message\{ServerRequestInterface, ResponseInterface};

class GetVehiclesAction extends ActionAbstract
{
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
