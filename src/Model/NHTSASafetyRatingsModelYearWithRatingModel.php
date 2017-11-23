<?php
declare (strict_types = 1);
namespace ModusCreate\Model;

use Exception;
use ModusCreate\Repository\NHTSASafetyRatingsVehicleIdRepository;
use ModusCreate\Model\NHTSASafetyRatingsModelYearModel;
use GuzzleHttp\Promise;

class NHTSASafetyRatingsModelYearWithRatingModel extends ModelAbstract
{
    /** response */
    private const OVERALL_RATING = 'OverallRating';
    private const VEHICLE_DESCRIPTION = NHTSASafetyRatingsModelYearModel::VEHICLE_DESCRIPTION;

    /** parameters */
    public const WITH_RATING = 'withRating';

    /** collection */
    public const CRASH_RATING = 'CrashRating';
    private const COUNT = NHTSASafetyRatingsModelYearModel::COUNT;
    private const RESULTS = NHTSASafetyRatingsModelYearModel::RESULTS;
    private const VEHICLE_ID = NHTSASafetyRatingsModelYearModel::VEHICLE_ID;

    /**
     * @var NHTSASafetyRatingsModelYearModel
     */
    private $yearModel;

    /**
     * @param NHTSASafetyRatingsVehicleIdRepository $repository
     * @param NHTSASafetyRatingsModelYearModel $yearModel
     */
    public function __construct(
        NHTSASafetyRatingsVehicleIdRepository $repository,
        NHTSASafetyRatingsModelYearModel $yearModel
    ) {
        $this->yearModel = $yearModel;
        parent::__construct($repository);
    }

    /**
     * @param array $data
     * @return array
     */
    public function find(array $data) : array
    {
        $collection = $this->yearModel->find($data);

        if (isset($collection[self::COUNT]) && $collection[self::COUNT] === 0 || $data[self::WITH_RATING] !== 'true') {
            return $collection;
        }

        return $this->findWithRating($collection);
    }

    /**
     * @param array $collection
     * @return array
     */
    private function findWithRating(array $collection) : array
    {
        if (isset($collection[self::COUNT]) && $collection[self::COUNT] > 0) {
            $promiseList = [];

            foreach ($collection[self::RESULTS] as $element) {
                $vehicleId = $element[self::VEHICLE_ID];
                $promiseList[] = $this->repository->find([
                    self::VEHICLE_ID => $vehicleId
                ]);
            }

            try {
                $responses = Promise\unwrap($promiseList);
                $collection = $this->normalizeResponsesCollection($responses);
            } catch (Exception $exception) {
                return [
                    self::COUNT => 0,
                    self::RESULTS => []
                ];
            }
        }

        return $collection;
    }

    /**
     * @param array $responses
     * @return array
     */
    private function normalizeResponsesCollection(array $responses) : array
    {
        $collection = [
            self::COUNT => 0,
            self::RESULTS => []
        ];

        foreach ($responses as $response) {
            $elements = $this->yearModel->normalizeCollection(
                json_decode((string) $response->getBody(), true)
            );

            if ($elements[self::COUNT] > 0) {
                $collection[self::COUNT] += $elements[self::COUNT];

                foreach ($elements[self::RESULTS] as $key => $element) {
                    $element = $this->normalizeCollectionElement($element);
                    $collection[self::RESULTS][] = $this->replaceVehicleDescriptionAndOveralRatingnKey($element);
                }
            }
        }

        return $collection;
    }

    /**
     * @param array $element
     * @return array
     */
    private function normalizeCollectionElement(array $element) : array
    {
        $allowed = [
            self::VEHICLE_DESCRIPTION,
            self::VEHICLE_ID,
            self::OVERALL_RATING
        ];
        return $this->yearModel->normalize($element, $allowed);
    }

    /**
     * @param array $element
     * @return array
     */
    private function replaceVehicleDescriptionAndOveralRatingnKey(array $element) : array
    {
        $element = $this->yearModel->replaceVehicleDescriptionKey($element);

        if (array_key_exists(self::OVERALL_RATING, $element)) {
            $element[self::CRASH_RATING] = $element[self::OVERALL_RATING];
            unset($element[self::OVERALL_RATING]);
        }
        return $element;
    }
}
