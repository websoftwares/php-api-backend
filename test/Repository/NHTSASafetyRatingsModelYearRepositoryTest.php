<?php
declare (strict_types = 1);
namespace ModusCreate\Test\Repository;

use ModusCreate\Repository\NHTSASafetyRatingsModelYearRepository;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Promise\FulfilledPromise;
use GuzzleHttp\Promise\PromiseInterface;

class NHTSASafetyRatingsModelYearRepositoryTest extends TestCase
{
    private $NHTSASafetyRatingsModelYearRepository;

    public function setUp()
    {
        parent::setUp();

        $this->clientMock = $this
            ->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->setMethods(['getAsync'])
            ->getMock();

        $this->NHTSASafetyRatingsModelYearRepository = new NHTSASafetyRatingsModelYearRepository(
            $this->clientMock
        );
    }

    /**
     * @dataProvider responseDataProvider
     * @param FulfilledPromise $promise
     * @return void
     */
    public function testFindSucceeds(FulfilledPromise $promise)
    {
        $parameters = [
            'modelYear' => 2015,
            'manufacturer' => 'Audi',
            'model' => 'A3'
        ];

        $endpoint = $this->NHTSASafetyRatingsModelYearRepository->createEndpointFromParameters($parameters);

        $this->clientMock->expects($this->once())->method('getAsync')
            ->with($this->equalTo($endpoint))
            ->will($this->returnValue($promise));

        $promiseFulfilled = $this->NHTSASafetyRatingsModelYearRepository->find($parameters);

        $this->assertInstanceOf(PromiseInterface::class, $promiseFulfilled);
        $actual = $promiseFulfilled->wait();
        $this->assertEquals(200, $actual->getStatusCode());
        $this->assertEquals('application/json; charset=utf-8', $actual->getHeaders()['Content-Type'][0]);
        $this->assertEquals($this->getFixture(), $actual->getBody());
    }

    /**
     * @return array
     */
    public function responseDataProvider() : array
    {
        return [
            [
                new FulfilledPromise(
                    new Response(
                        200,
                        ['Content-Type' => 'application/json; charset=utf-8'],
                        $this->getFixture()
                    )
                )
            ]
        ];
    }

    private function getFixture()
    {
        return file_get_contents(__DIR__ . '/../fixtures/modelyear.json');
    }
}
