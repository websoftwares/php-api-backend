<?php
declare(strict_types=1);
namespace ModusCreate\Repository;

class NHTSASafetyRatingsModelYearRepository extends RepositoryAbstract
{
    protected const ENDPOINT = '/SafetyRatings/modelyear/%s/make/%s/model/%s?format=json';
}
