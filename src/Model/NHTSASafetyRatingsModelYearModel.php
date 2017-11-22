<?php
declare (strict_types = 1);
namespace ModusCreate\Model;

use GuzzleHttp\Promise\RejectionException;

class NHTSASafetyRatingsModelYearModel extends ModelAbstract
{
    /** collection */
    private const COUNT = 'Count';
    private const RESULTS = 'Results';
    private const DESCRIPTION = 'Description';
    private const VEHICLE_ID = 'VehicleId';

    /** response */
    private const VEHICLE_DESCRIPTION = 'VehicleDescription';

    /**
     * @param array $data
     * @return array
     */
    public function find(array $data) : array
    {
        try {
            $response = $this->repository->find($data)->wait();
            $collection = json_decode((string)$response->getBody(), true);

            return $this->normalizeCollection(
                $this->replaceVehicleDescriptionKeyInCollection($collection)
            );
        } catch (RejectionException $rejectedPromise) {
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
    private function normalizeCollection(array $collection) : array
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
    private function normalize(array $data, array $allowed) : array
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
            // We can safely use array_key_exists in php 7.1+ the speed is somewhat slower then isset
            // but allows NULL as value where isset will return false
            foreach ($collection[self::RESULTS] as $key => $element) {
                $element = $this->normalizeCollectionElement($element);
                if (array_key_exists(self::VEHICLE_DESCRIPTION, $element)) {
                    $element[self::DESCRIPTION] = $element[self::VEHICLE_DESCRIPTION];
                    unset($element[self::VEHICLE_DESCRIPTION]);
                    $collection[self::RESULTS][$key] = $element;
                }
            }
        }

        return $collection;
    }
}
