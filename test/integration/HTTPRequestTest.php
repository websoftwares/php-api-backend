<?php
declare (strict_types = 1);
namespace ModusCreate\Test\Integration;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use ModusCreate\Model\NHTSASafetyRatingsModelYearModel;

class HTTPRequestTest extends TestCase
{
    /**
     * @var Client
     */
    private $client;

    public function setUp()
    {
        $this->client = new Client;
        parent::setUp();
    }

    /**
     * @dataProvider requestProvider
     *
     * @param Request $request
     * @param string $expected
     * @return void
     */
    public function testEndpointSucceeds(Request $request, string $expected)
    {
        $actual = $this->client->send($request);
        $this->assertEquals($expected, (string)$actual->getBody());
    }

    /**
     * @return void
     */
    public function requestProvider() : array
    {
        $notFoundResponse = file_get_contents(__DIR__ . '/../fixtures/notFound.json');
        $postHeaders = ['Content-Type' => 'application/json; charset=utf-8'];

        return [
            [
                new Request('GET', 'http://app:8000/vehicles/2015/Audi/A3'),
                file_get_contents(__DIR__ . '/../fixtures/2015AudiA3.json')
            ],
            [
                new Request('GET', 'http://app:8000/vehicles/2015/Toyota/Yaris'),
                file_get_contents(__DIR__ . '/../fixtures/2015ToyotaYaris.json')
            ],
            [
                new Request('GET', 'http://app:8000/vehicles/2015/Ford/Crown%20Victoria'),
                $notFoundResponse
            ],
            [
                new Request('GET', 'http://app:8000/vehicles/undefined/Ford/Fusion'),
                $notFoundResponse
            ],
            [
                new Request('POST', 'http://app:8000/vehicles', $postHeaders, json_encode(
                    [
                        NHTSASafetyRatingsModelYearModel::MODEL_YEAR => 2015,
                        NHTSASafetyRatingsModelYearModel::MANUFACTURER => 'Audi',
                        NHTSASafetyRatingsModelYearModel::MODEL => 'A3'
                    ]
                )),
                file_get_contents(__DIR__ . '/../fixtures/2015AudiA3.json')
            ],
            [
                new Request('POST', 'http://app:8000/vehicles', $postHeaders, json_encode(
                    [
                        NHTSASafetyRatingsModelYearModel::MODEL_YEAR => 2015,
                        NHTSASafetyRatingsModelYearModel::MANUFACTURER => 'Toyota',
                        NHTSASafetyRatingsModelYearModel::MODEL => 'Yaris'
                    ]
                )),
                file_get_contents(__DIR__ . '/../fixtures/2015ToyotaYaris.json')
            ],
            [
                new Request('POST', 'http://app:8000/vehicles', $postHeaders, json_encode(
                    [
                        NHTSASafetyRatingsModelYearModel::MANUFACTURER => 'Honda',
                        NHTSASafetyRatingsModelYearModel::MODEL => 'Accord'
                    ]
                )),
                $notFoundResponse
            ],
            [
                new Request('GET', 'http://app:8000/vehicles/2015/Audi/A3?withRating=true'),
                file_get_contents(__DIR__ . '/../fixtures/2015AudiA3Rating.json')
            ],
            [
                new Request('GET', 'http://app:8000/vehicles/2015/Audi/A3?withRating=false'),
                file_get_contents(__DIR__ . '/../fixtures/2015AudiA3.json')
            ],
            [
                new Request('GET', 'http://app:8000/vehicles/2015/Audi/A3?withRating=bananas'),
                file_get_contents(__DIR__ . '/../fixtures/2015AudiA3.json')
            ],
        ];
    }
}
