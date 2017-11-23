<?php
declare (strict_types = 1);
namespace ModusCreate\Action;

use Psr\Http\Message\{ServerRequestInterface, ResponseInterface};

class PostVehiclesAction extends ActionAbstract
{
    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return ResponseInterface
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response
    ) : ResponseInterface {
        $body = json_decode((string) $request->getBody(), true);
        $result = $this->model->find($body);
        return $response->withJson($result);
    }
}
