<?php
declare (strict_types = 1);
namespace ModusCreate\Test\Repository;

use ModusCreate\Repository\NHTSASafetyRatingsModelYearRepository;
use ModusCreate\Model\NHTSASafetyRatingsModelYearModel;

class NHTSASafetyRatingsModelYearRepositoryTest extends RepositoryTestAbstract
{
    /**
     * @return string
     */
    protected function getRepositoryClassName() : string
    {
        return NHTSASafetyRatingsModelYearRepository::class;
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
    protected function getEndpoint() : string
    {
        return 'SafetyRatings/modelyear/2005/make/MERCEDES-BENZ/model/SLK-CLASS?format=json';
    }

    /**
     * @return string
     */
    protected function getFixture() : string
    {
        return file_get_contents(__DIR__ . '/../fixtures/modelyear.json');
    }
}
