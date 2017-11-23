<?php
declare (strict_types = 1);
namespace ModusCreate\Test\Repository;

use ModusCreate\Repository\NHTSASafetyRatingsVehicleIdRepository;

class NHTSASafetyRatingsVehicleIdRepositoryTest extends RepositoryTestAbstract
{
    /**
     * @return string
     */
    protected function getRepositoryClassName() : string
    {
        return NHTSASafetyRatingsVehicleIdRepository::class;
    }

    /**
     * @return array
     */
    protected function getParameters() : array
    {
        return [
            'VehicleId' => 9594,
        ];
    }

    /**
     * @return string
     */
    protected function getEndpoint() : string
    {
        return 'SafetyRatings/VehicleId/9594?format=json';
    }

    /**
     * @return string
     */
    protected function getFixture() : string
    {
        return file_get_contents(__DIR__ . '/../fixtures/vehicleId.json');
    }
}
