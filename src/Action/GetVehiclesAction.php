<?php
declare (strict_types = 1);
namespace ModusCreate\Action;

use Psr\Http\Message\{ServerRequestInterface, ResponseInterface};
use ModusCreate\Model\NHTSASafetyRatingsModelYearWithRatingModel;

class GetVehiclesAction extends ActionAbstract
{
    /** get parameters */
    private const WITH_RATING = NHTSASafetyRatingsModelYearWithRatingModel::WITH_RATING;

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return ResponseInterface
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ) : ResponseInterface {
        $args[self::WITH_RATING] = $request->getQueryParam(self::WITH_RATING, '');
        $result = $this->model->find($args);

        return $response->withJson($result);
    }
}
