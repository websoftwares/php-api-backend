<?php
declare (strict_types = 1);
namespace ModusCreate\Model;

use Exception;

class NHTSASafetyRatingsModelYearModel extends ModelAbstract
{
    /** collection */
    public const COUNT = 'Count';
    public const RESULTS = 'Results';
    public const DESCRIPTION = 'Description';
    public const VEHICLE_ID = 'VehicleId';

    /** response */
    public const VEHICLE_DESCRIPTION = 'VehicleDescription';

    /** parameters */
    public const MODEL_YEAR = 'modelYear';
    public const MANUFACTURER = 'manufacturer';
    public const MODEL = 'model';
    private const UNDEFINED = 'undefined';

    /**
     * @param array $data
     * @return array
     */
    public function find(array $data) : array
    {
        $parameters = [
            self::MODEL_YEAR => $data[self::MODEL_YEAR] ?? self::UNDEFINED,
            self::MANUFACTURER => $data[self::MANUFACTURER] ?? self::UNDEFINED,
            self::MODEL => $data[self::MODEL] ?? self::UNDEFINED,
        ];

        try {
            $response = $this->repository->find($parameters)->wait();
            $collection = json_decode((string)$response->getBody(), true);

            return $this->normalizeCollection(
                $this->replaceVehicleDescriptionKeyInCollection($collection)
            );
        } catch (Exception $exception) {
            // We should do something better with the exception handling
            return [
                self::COUNT => 0,
                self::RESULTS => []
            ];
        }
    }

    /**
     * @param array $collection
     * @return array
     */
    public function normalizeCollection(array $collection) : array
    {
        $allowed = [self::COUNT, self::RESULTS];
        return $this->normalize($collection, $allowed);
    }

    /**
     * @param array $element
     * @return array
     */
    private function normalizeCollectionElement(array $element) : array
    {
        $allowed = [self::VEHICLE_DESCRIPTION, self::VEHICLE_ID];
        return $this->normalize($element, $allowed);
    }

    /**
     * @param array $data
     * @param array $allowed
     * @return array
     */
    public function normalize(array $data, array $allowed) : array
    {
        return array_intersect_key($data, array_flip($allowed));
    }

    /**
     * @param array $collection
     * @return array
     */
    private function replaceVehicleDescriptionKeyInCollection(array $collection) : array
    {
        // Only do this operation if we have something to work with
        if ($collection[self::COUNT] > 0) {
            foreach ($collection[self::RESULTS] as $key => $element) {
                $element = $this->normalizeCollectionElement($element);
                $collection[self::RESULTS][$key] = $this->replaceVehicleDescriptionKey($element);
            }
        }

        return $collection;
    }

    /**
     * @param array $element
     * @return array
     */
    public function replaceVehicleDescriptionKey(array $element) : array
    {
        if (array_key_exists(self::VEHICLE_DESCRIPTION, $element)) {
            $element[self::DESCRIPTION] = $element[self::VEHICLE_DESCRIPTION];
            unset($element[self::VEHICLE_DESCRIPTION]);
        }

        return $element;
    }
}
