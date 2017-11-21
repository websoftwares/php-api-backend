<?php
declare(strict_types=1);
namespace ModusCreate\Factory;

use GuzzleHttp\Client;

class NHTSAClientFactory
{
    private const BASE_URI = 'https://one.nhtsa.gov/webapi/api';
    private const BASE_URI_KEY = 'base_uri';

    /**
     * @return Client
     */
    public static function createClient(): Client
    {
        return new Client([
            self::BASE_URI_KEY => self::BASE_URI
        ]);
    }
}
