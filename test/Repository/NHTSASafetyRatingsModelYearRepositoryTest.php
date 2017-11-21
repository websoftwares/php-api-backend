<?php
declare (strict_types = 1);
namespace ModusCreate\Test\Repository;

use ModusCreate\Repository\NHTSASafetyRatingsModelYearRepository;

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
            'modelYear' => 2015,
            'manufacturer' => 'Audi',
            'model' => 'A3'
        ];
    }

    /**
     * @return string
     */
    protected function getEndpoint() : string
    {
        return '/SafetyRatings/modelyear/2015/make/Audi/model/A3?format=json';
    }

    /**
     * @return string
     */
    protected function getFixture() : string
    {
        return file_get_contents(__DIR__ . '/../fixtures/modelyear.json');
    }
}
