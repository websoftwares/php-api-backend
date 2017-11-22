<?php
namespace ModusCreate\Test\Action;

use ModusCreate\Action\GetVehiclesAction;
use PHPUnit\Framework\TestCase;
use Slim\Http\{Environment, Request, Response};
use ModusCreate\Model\ModelInterface;

class GetVehiclesActionTest extends TestCase
{
    /**
     * @var ModelInterface
     */
    private $model;

    /**
     * @var GetVehiclesAction
     */
    private $action;

    public function setUp()
    {
        $this->model = $this
            ->getMockBuilder(ModelInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['find'])
            ->getMock();

        $this->action = new GetVehiclesAction($this->model);

        parent::setUp();
    }

    /**
     * @dataProvider environmentDataProvider
     *
     * @param array $environment
     * @param array $args
     * @param string $expected
     * @return void
     */
    public function testGetVehiclesActionSucceeds(array $environment, array $args, string $expected)
    {
        $env = Environment::mock($environment);
        $request = Request::createFromEnvironment($env);
        $response = new Response;

        $this->model->expects($this->once())->method('find')
            ->with($this->equalTo($args))
            ->will($this->returnValue(json_decode($expected, true)));

        $action = $this->action;
        $response = $action($request, $response, $args);
        $this->assertEquals((string)$response->getBody(), $expected);
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
                    'model_year' => 2015,
                    'manufacturer' => 'Audi',
                    'model' => 'A3'
                ],
                file_get_contents(__DIR__ . '/../fixtures/2015AudiA3.json')
            ],
            [
                [
                    'REQUEST_METHOD' => 'GET',
                    'REQUEST_URI' => '/vehicles/2015/Toyota/Yaris'
                ],
                [
                    'model_year' => 2015,
                    'manufacturer' => 'Toyota',
                    'model' => 'Yaris'
                ],
                file_get_contents(__DIR__ . '/../fixtures/2015ToyotaYaris.json')
            ],
            [
                [
                    'REQUEST_METHOD' => 'GET',
                    'REQUEST_URI' => '/vehicles/2015/Ford/Crown%20Victoria'
                ],
                [
                    'model_year' => 2015,
                    'manufacturer' => 'Ford',
                    'model' => 'Crown Victoria'
                ],
                file_get_contents(__DIR__ . '/../fixtures/notFound.json')
            ],
            [
                [
                    'REQUEST_METHOD' => 'GET',
                    'REQUEST_URI' => '/vehicles/undefined/Ford/Fusion'
                ],
                [
                    'model_year' => 'undefined',
                    'manufacturer' => 'Ford',
                    'model' => 'Fusion'
                ],
                file_get_contents(__DIR__ . '/../fixtures/notFound.json')
            ]
        ];
    }
}
