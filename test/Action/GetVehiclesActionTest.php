<?php
namespace ModusCreate\Test\Action;

use ModusCreate\Action\GetVehiclesAction;
use ModusCreate\Model\NHTSASafetyRatingsModelYearModel;

class GetVehiclesActionTest extends ActionTestAbstract
{

    /**
     * @return string
     */
    protected function getActionClassName() : string
    {
        return GetVehiclesAction::class;
    }

    /**
     * @return array
     */
    public function environmentDataProvider() : array
    {
        return [
            [
                [
                    'REQUEST_METHOD' => 'GET',
                    'REQUEST_URI' => '/vehicles/2015/Audi/A3'
                ],
                [
                    NHTSASafetyRatingsModelYearModel::MODEL_YEAR => 2015,
                    NHTSASafetyRatingsModelYearModel::MANUFACTURER => 'Audi',
                    NHTSASafetyRatingsModelYearModel::MODEL => 'A3'
                ],
                file_get_contents(__DIR__ . '/../fixtures/2015AudiA3.json')
            ],
            [
                [
                    'REQUEST_METHOD' => 'GET',
                    'REQUEST_URI' => '/vehicles/2015/Toyota/Yaris'
                ],
                [
                    NHTSASafetyRatingsModelYearModel::MODEL_YEAR => 2015,
                    NHTSASafetyRatingsModelYearModel::MANUFACTURER => 'Toyota',
                    NHTSASafetyRatingsModelYearModel::MODEL => 'Yaris'
                ],
                file_get_contents(__DIR__ . '/../fixtures/2015ToyotaYaris.json')
            ],
            [
                [
                    'REQUEST_METHOD' => 'GET',
                    'REQUEST_URI' => '/vehicles/2015/Ford/Crown%20Victoria'
                ],
                [
                    NHTSASafetyRatingsModelYearModel::MODEL_YEAR => 2015,
                    NHTSASafetyRatingsModelYearModel::MANUFACTURER => 'Ford',
                    NHTSASafetyRatingsModelYearModel::MODEL => 'Crown Victoria'
                ],
                file_get_contents(__DIR__ . '/../fixtures/notFound.json')
            ],
            [
                [
                    'REQUEST_METHOD' => 'GET',
                    'REQUEST_URI' => '/vehicles/undefined/Ford/Fusion'
                ],
                [
                    NHTSASafetyRatingsModelYearModel::MODEL_YEAR => 'undefined',
                    NHTSASafetyRatingsModelYearModel::MANUFACTURER => 'Ford',
                    NHTSASafetyRatingsModelYearModel::MODEL => 'Crown Victoria'
                ],
                file_get_contents(__DIR__ . '/../fixtures/notFound.json')
            ]
        ];
    }
}
