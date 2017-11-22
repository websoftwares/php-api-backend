<?php
declare (strict_types = 1);
namespace ModusCreate\Factory;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

class NHTSAClientFactory
{
    private const BASE_URI = 'https://one.nhtsa.gov/webapi/api/';
    private const BASE_URI_KEY = 'base_uri';

    /**
     * @return ClientInterface
     */
    public static function newInstance() : ClientInterface
    {
        return new Client([
            self::BASE_URI_KEY => self::BASE_URI
        ]);
    }
}
