<?php
declare (strict_types = 1);
namespace ModusCreate\Repository;

use GuzzleHttp\Promise\PromiseInterface;

interface RepositoryInterface
{
    /**
     * @param array $params
     * @return array
     */
    public function find(array $params) : PromiseInterface;

    /**
     * @return string
     */
    public function createEndpointFromParameters(array $parameters) : string;
}
