<?php
declare (strict_types = 1);
namespace ModusCreate\Test\Integration;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class HTTPRequestTest extends TestCase
{
    /**
     * @var Client
     */
    private $client;

    public function setUp()
    {
        $this->client = new Client;
        parent::setUp();
    }

    /**
     * @dataProvider requestProvider
     *
     * @param Request $request
     * @param string $expected
     * @return void
     */
    public function testEndpointSucceeds(Request $request, string $expected)
    {
        $actual = $this->client->send($request);
        $this->assertEquals($expected, (string)$actual->getBody());
    }

    /**
     * @return void
     */
    public function requestProvider() : array
    {
        $notFoundResponse = file_get_contents(__DIR__ . '/../fixtures/notFound.json');

        return [
            [
                new Request('GET', 'http://app:8000/vehicles/2015/Audi/A3'),
                file_get_contents(__DIR__ . '/../fixtures/2015AudiA3.json')
            ],
            [
                new Request('GET', 'http://app:8000/vehicles/2015/Toyota/Yaris'),
                file_get_contents(__DIR__ . '/../fixtures/2015ToyotaYaris.json')
            ],
            [
                new Request('GET', 'http://app:8000/vehicles/2015/Ford/Crown%20Victoria'),
                $notFoundResponse
            ],
            [
                new Request('GET', 'http://app:8000/vehicles/undefined/Ford/Fusion'),
                $notFoundResponse
            ]
        ];
    }
}
