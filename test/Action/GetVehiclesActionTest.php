<?php
namespace ModusCreate\Test\Action;

use ModusCreate\Action\GetVehiclesAction;
use PHPUnit\Framework\TestCase;
use Slim\Http\Environment;
use Slim\Http\Request;
use Slim\Http\Response;

class GetVehiclesActionTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * @dataProvider environmentDataProvider
     * @param array $environment
     * @return void
     */
    public function testGetVehiclesActionSucceeds(array $environment)
    {
        $env = Environment::mock($environment);
        $request = Request::createFromEnvironment($env);
        $response = new Response;

        $action = new GetVehiclesAction;

        $response = $action($request, $response, []);
        $this->assertEquals((string)$response->getBody(), '[]');
    }

    /**
     * @return void
     */
    public function environmentDataProvider()
    {
        return [
            [
                [
                    'REQUEST_METHOD' => 'GET',
                    'REQUEST_URI' => '/vehicles/2015/Audi/A3'
                ],
                [
                    'REQUEST_METHOD' => 'GET',
                    'REQUEST_URI' => '/vehicles/2015/Toyota/Yaris'
                ],
                [
                    'REQUEST_METHOD' => 'GET',
                    'REQUEST_URI' => '/vehicles/2015/Ford/Crown Victoria'
                ],
                [
                    'REQUEST_METHOD' => 'GET',
                    'REQUEST_URI' => '/vehicles/undefined/Ford/Fusion'
                ]
            ]
        ];
    }
}
