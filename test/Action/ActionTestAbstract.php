<?php
namespace ModusCreate\Test\Action;

use ModusCreate\Action\GetVehiclesAction;
use PHPUnit\Framework\TestCase;
use Slim\Http\{Environment, Request, Response};
use ModusCreate\Model\{ModelInterface};
use Slim\Http\RequestBody;

abstract class ActionTestAbstract extends TestCase
{
    /**
     * @var ModelInterface
     */
    private $model;

    /**
     * @var ActionAbstract
     */
    private $action;

    public function setUp()
    {
        $this->model = $this
            ->getMockBuilder(ModelInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['find'])
            ->getMock();

        $actionClass = $this->getActionClassName();
        $this->action = new $actionClass($this->model);

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
    public function testMethodActionSucceeds(array $environment, array $args, string $expected)
    {
        $env = Environment::mock($environment);
        $request = Request::createFromEnvironment($env);
        $response = new Response;

        $body = new RequestBody;
        $body->write(json_encode($args));

        $request = $request->withBody($body);

        $this->model->expects($this->once())->method('find')
            ->with($this->equalTo($args))
            ->will($this->returnValue(json_decode($expected, true)));

        $action = $this->action;
        $response = $action($request, $response, $args);
        $this->assertEquals((string)$response->getBody(), $expected);
    }

    /**
     * @return array
     */
    abstract public function environmentDataProvider() : array;

    /**
     * @return string
     */
    abstract protected function getActionClassName() : string;
}
