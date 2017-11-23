<?php
namespace ModusCreate\Test\Action;

use ModusCreate\Action\GetVehiclesAction;
use ModusCreate\Model\{NHTSASafetyRatingsModelYearModel, NHTSASafetyRatingsModelYearWithRatingModel};

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
                    NHTSASafetyRatingsModelYearModel::MODEL => 'A3',
                    NHTSASafetyRatingsModelYearWithRatingModel::WITH_RATING => ''
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
                    NHTSASafetyRatingsModelYearModel::MODEL => 'Yaris',
                    NHTSASafetyRatingsModelYearWithRatingModel::WITH_RATING => ''
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
                    NHTSASafetyRatingsModelYearModel::MODEL => 'Crown Victoria',
                    NHTSASafetyRatingsModelYearWithRatingModel::WITH_RATING => ''
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
                    NHTSASafetyRatingsModelYearModel::MODEL => 'Fusion',
                    NHTSASafetyRatingsModelYearWithRatingModel::WITH_RATING => ''
                ],
                file_get_contents(__DIR__ . '/../fixtures/notFound.json')
            ]
        ];
    }
}
