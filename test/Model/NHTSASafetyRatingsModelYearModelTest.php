<?php
declare (strict_types = 1);
namespace ModusCreate\Test\Model;

use PHPUnit\Framework\TestCase;
use ModusCreate\Repository\RepositoryInterface;
use ModusCreate\Model\NHTSASafetyRatingsModelYearModel;
use GuzzleHttp\Promise\{FulfilledPromise, PromiseInterface, RejectedPromise};
use GuzzleHttp\Psr7\Response;

class NHTSASafetyRatingsModelYearModelTest extends TestCase
{
    /**
     * @var RepositoryInterface
     */
    private $repository;

    /**
     * @var NHTSASafetyRatingsModelYear
     */
    private $model;

    public function setUp()
    {
        $this->repository = $this
            ->getMockBuilder(RepositoryInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['find', 'createEndpointFromParameters'])
            ->getMock();

        $this->model = new NHTSASafetyRatingsModelYearModel($this->repository);
    }

    /**
     * @dataProvider successPromiseParametersDataProvider
     * @param FulfilledPromise $promise
     * @param array $parameters
     * @return void
     */
    public function testFindSucceeds(FulfilledPromise $promise, array $parameters)
    {
        $expected = [
            'Count' => 1,
            'Results' => [
                [
                    'VehicleId' => 1417,
                    'Description' => '2005 Mercedes-Benz SLK-Class - Convertible'
                ]
            ]
        ];

        $this->repository->expects($this->once())->method('find')
            ->with($this->equalTo($parameters))
            ->will($this->returnValue($promise));

        $actual = $this->model->find($parameters);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @dataProvider notFoundPromiseParametersDataProvider
     * @param FulfilledPromise $promise
     * @param array $parameters
     * @return void
     */
    public function testNotFoundFindSucceeds(FulfilledPromise $promise, array $parameters)
    {
        $expected = [
            'Count' => 0,
            'Results' => []
        ];

        $this->repository->expects($this->once())->method('find')
            ->with($this->equalTo($parameters))
            ->will($this->returnValue($promise));

        $actual = $this->model->find($parameters);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @dataProvider failsPromiseParametersDataProvider
     * @param RejectedPromise $promise
     * @param array $parameters
     * @return void
     */
    public function testFindFails(RejectedPromise $promise, array $parameters)
    {
        $expected = [
            'Count' => 0,
            'Results' => []
        ];

        $this->repository->expects($this->once())->method('find')
            ->with($this->equalTo($parameters))
            ->will($this->returnValue($promise));

        $actual = $this->model->find($parameters);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @return array
     */
    public function successPromiseParametersDataProvider() : array
    {
        return [
            [
                new FulfilledPromise(
                    new Response(
                        200,
                        ['Content-Type' => 'application/json; charset=utf-8'],
                        $this->getSuccessFixture()
                    )
                ),
                $this->getParameters()
            ]
        ];
    }

    /**
     * @return array
     */
    public function notFoundPromiseParametersDataProvider() : array
    {
        return [
            [
                new FulfilledPromise(
                    new Response(
                        200,
                        ['Content-Type' => 'application/json; charset=utf-8'],
                        $this->getNotFoundFixture()
                    )
                ),
                $this->getParameters()
            ]
        ];
    }

    /**
     * @return array
     */
    public function failsPromiseParametersDataProvider() : array
    {
        return [
            [
                new RejectedPromise(
                    'Error'
                ),
                $this->getParameters()

            ]
        ];
    }

    /**
     * @return array
     */
    protected function getParameters() : array
    {
        return [
            NHTSASafetyRatingsModelYearModel::MODEL_YEAR => 2005,
            NHTSASafetyRatingsModelYearModel::MANUFACTURER => 'MERCEDES-BENZ',
            NHTSASafetyRatingsModelYearModel::MODEL => 'SLK-CLASS'
        ];
    }

    /**
     * @return string
     */
    protected function getSuccessFixture() : string
    {
        return file_get_contents(__DIR__ . '/../fixtures/modelyear.json');
    }

    protected function getNotFoundFixture() : string
    {
        return file_get_contents(__DIR__ . '/../fixtures/noResult.json');
    }
}
