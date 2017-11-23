<?php
declare (strict_types = 1);
namespace ModusCreate\Test\Repository;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Promise\{FulfilledPromise, PromiseInterface, RejectedPromise};
use GuzzleHttp\Psr7\Response;

abstract class RepositoryTestAbstract extends TestCase
{
    /**
     * @var RepositoryInterface
     */
    private $repository;

    /**
     * @var Client
     */
    private $clientMock;

    public function setUp()
    {
        $this->clientMock = $this
            ->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->setMethods(['getAsync'])
            ->getMock();

        $repository = $this->getRepositoryClassName();

        $this->repository = new $repository(
            $this->clientMock
        );

        parent::setUp();
    }

    /**
     * @dataProvider successPromiseParametersEndpointDataProvider
     * @param FulfilledPromise $promise
     * @param array $parameters
     * @param string $endpoint
     * @return void
     */
    public function testFindSucceeds(FulfilledPromise $promise, array $parameters, string $endpoint)
    {
        $this->clientMock->expects($this->once())->method('getAsync')
            ->with($this->equalTo($endpoint))
            ->will($this->returnValue($promise));

        $promiseFulfilled = $this->repository->find($parameters);

        $this->assertInstanceOf(PromiseInterface::class, $promiseFulfilled);
        $actual = $promiseFulfilled->wait();
        $this->assertEquals(200, $actual->getStatusCode());
        $this->assertEquals('application/json; charset=utf-8', $actual->getHeaders()['Content-Type'][0]);
        $this->assertEquals($this->getFixture(), $actual->getBody());
    }

    /**
     * @dataProvider failsPromiseParametersEndpointDataProvider
     * @expectedException GuzzleHttp\Promise\RejectionException
     * @expectedExceptionMessage The promise was rejected with reason: Error
     * @param RejectedPromise $promise
     * @param array $parameters
     * @param string $endpoint
     * @return void
     */
    public function testFindFails(RejectedPromise $promise, array $parameters, string $endpoint)
    {
        $this->clientMock->expects($this->once())->method('getAsync')
            ->with($this->equalTo($endpoint))
            ->will($this->returnValue($promise));

        $this->repository->find($parameters)->wait();
    }

    /**
     * @return array
     */
    public function successPromiseParametersEndpointDataProvider() : array
    {
        return [
            [
                new FulfilledPromise(
                    new Response(
                        200,
                        ['Content-Type' => 'application/json; charset=utf-8'],
                        $this->getFixture()
                    )
                ),
                $this->getParameters(),
                $this->getEndpoint()
            ]
        ];
    }

    /**
     * @return array
     */
    public function failsPromiseParametersEndpointDataProvider() : array
    {
        return [
            [
                new RejectedPromise(
                    'Error'
                ),
                $this->getParameters(),
                $this->getEndpoint()
            ]
        ];
    }

    /**
     * @return string
     */
    abstract protected function getRepositoryClassName() : string;

    /**
     * @return string
     */
    abstract protected function getFixture() : string;

    /**
     * @return string
     */
    abstract protected function getParameters() : array;

    /**
     * @return string
     */
    abstract protected function getEndpoint() : string;
}
