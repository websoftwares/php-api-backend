<?php
namespace ModusCreate\Test\Action;

use ModusCreate\Action\PostVehiclesAction;
use ModusCreate\Model\NHTSASafetyRatingsModelYearModel;

class PostVehiclesActionTest extends ActionTestAbstract
{

    /**
     * @return string
     */
    protected function getActionClassName() : string
    {
        return PostVehiclesAction::class;
    }

    /**
     * @return void
     */
    public function environmentDataProvider() : array
    {
        return [
            [
                [
                    'REQUEST_METHOD' => 'POST',
                    'REQUEST_URI' => '/vehicles'
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
                    'REQUEST_METHOD' => 'POST',
                    'REQUEST_URI' => '/vehicles'
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
                    'REQUEST_METHOD' => 'POST',
                    'REQUEST_URI' => '/vehicles'
                ],
                [
                    NHTSASafetyRatingsModelYearModel::MANUFACTURER => 'Honda',
                    NHTSASafetyRatingsModelYearModel::MODEL => 'Accord'
                ],
                file_get_contents(__DIR__ . '/../fixtures/notFound.json')
            ]
        ];
    }
}
