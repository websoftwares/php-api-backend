<?php
declare (strict_types = 1);
namespace ModusCreate\Test\Model;

use PHPUnit\Framework\TestCase;
use ModusCreate\Repository\NHTSASafetyRatingsVehicleIdRepository;
use ModusCreate\Model\{NHTSASafetyRatingsModelYearModel, NHTSASafetyRatingsModelYearWithRatingModel};
use GuzzleHttp\Promise\{FulfilledPromise, PromiseInterface, RejectedPromise};
use GuzzleHttp\Psr7\Response;

class NHTSASafetyRatingsModelYearWithRatingModelTest extends TestCase
{
    /**
     * @var NHTSASafetyRatingsVehicleIdRepository
     */
    private $repository;

    /**
     * @var NHTSASafetyRatingsModelYearWithRatingModel
     */
    private $model;

    /**
     * @var NHTSASafetyRatingsModelYearModel
     */
    private $yearModel;

    public function setUp()
    {
        $this->repository = $this
            ->getMockBuilder(NHTSASafetyRatingsVehicleIdRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(['find', 'createEndpointFromParameters'])
            ->getMock();

        $this->yearModel = $this
            ->getMockBuilder(NHTSASafetyRatingsModelYearModel::class)
            ->disableOriginalConstructor()
            ->setMethods(['find'])
            ->getMock();

        $this->model = new NHTSASafetyRatingsModelYearWithRatingModel($this->repository, $this->yearModel);
    }

    /**
     * @dataProvider successPromiseParametersDataProvider
     * @param FulfilledPromise $promise
     * @param array $parameters
     * @return void
     */
    public function testFindSucceeds(FulfilledPromise $promise, array $parameters, array $response)
    {
        $expected = [
            NHTSASafetyRatingsModelYearModel::COUNT => 1,
            NHTSASafetyRatingsModelYearModel::RESULTS  => [
                [
                    NHTSASafetyRatingsModelYearModel::VEHICLE_ID => 1417,
                    NHTSASafetyRatingsModelYearModel::DESCRIPTION => '2005 Mercedes-Benz SLK-Class - Convertible',
                    NHTSASafetyRatingsModelYearWithRatingModel::CRASH_RATING => 'Not Rated'
                ]
            ]
        ];

        $this->yearModel->expects($this->once())->method('find')
            ->with($this->equalTo($parameters))
            ->will($this->returnValue($response));

        $this->repository->expects($this->once())->method('find')
            ->with($this->equalTo([NHTSASafetyRatingsModelYearModel::VEHICLE_ID => 1417]))
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
            NHTSASafetyRatingsModelYearModel::COUNT => 0,
            NHTSASafetyRatingsModelYearModel::RESULTS => []
        ];

        $this->yearModel->expects($this->once())->method('find')
            ->with($this->equalTo($parameters))
            ->will($this->returnValue($expected));

        $actual = $this->model->find($parameters);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @dataProvider failsPromiseParametersDataProvider
     * @param RejectedPromise $promise
     * @param array $parameters
     * @return void
     */
    public function testFindFails(RejectedPromise $promise, array $parameters, array $response)
    {
        $expected = [
            NHTSASafetyRatingsModelYearModel::COUNT => 0,
            NHTSASafetyRatingsModelYearModel::RESULTS => []
        ];

        $this->yearModel->expects($this->once())->method('find')
        ->with($this->equalTo($parameters))
        ->will($this->returnValue($response));

        $this->repository->expects($this->once())->method('find')
            ->with($this->equalTo([NHTSASafetyRatingsModelYearModel::VEHICLE_ID => 1417]))
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
                $this->getParameters(),
                $this->getModelResponse()
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
                $this->getParameters(),
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
                $this->getParameters(),
                $this->getModelResponse()
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
            NHTSASafetyRatingsModelYearModel::MODEL => 'SLK-CLASS',
            NHTSASafetyRatingsModelYearWithRatingModel::WITH_RATING => 'true'
        ];
    }

    /**
     * @return string
     */
    protected function getSuccessFixture() : string
    {
        return file_get_contents(__DIR__ . '/../fixtures/SLKRating.json');
    }

    /**
     * @return string
     */
    protected function getNotFoundFixture() : string
    {
        return file_get_contents(__DIR__ . '/../fixtures/noResult.json');
    }

    /**
     * @return array
     */
    protected function getModelResponse() : array
    {
        return json_decode(file_get_contents(__DIR__ . '/../fixtures/SLKVehicle.json'), true);
    }
}
