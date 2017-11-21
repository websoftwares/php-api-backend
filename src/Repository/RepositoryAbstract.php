<?php
declare(strict_types=1);
namespace ModusCreate\Repository;

use GuzzleHttp\Client;
use GuzzleHttp\Promise\PromiseInterface;

abstract class RepositoryAbstract implements RepositoryInterface
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param array $parameters
     * @return PromiseInterface
     */
    public function find(array $parameters): PromiseInterface
    {
        $endpoint = $this->createEndpointFromParameters($parameters);
        return $this->client->getAsync($endpoint);
    }

    /**
     * @param array $parameters
     * @return string
     */
    public function createEndpointFromParameters(array $parameters): string
    {
        return sprintf(static::ENDPOINT, ...array_values($parameters));
    }
}
