<?php

namespace App\Controller;

use App\Entity\City;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Nelmio\ApiDocBundle\Attribute\Model;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;

#[Route('/city')]
#[OA\Tag(name: 'City')]
class CityController extends BaseController
{
    #[Route(name: 'show_all_cities', methods: ['GET'])]
    #[OA\Get(summary: 'Get paginated list of Cities', parameters: [
            new OA\Parameter(name: 'page', in: 'query', required: false, schema: new OA\Schema(type: 'int', example: 1)),
            new OA\Parameter(name: 'limit', in: 'query', required: false, schema: new OA\Schema(type: 'int', example: 1))
    ])]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Get paginated list of Cities',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(
                properties: [
                    new OA\Property(property: 'data', type: 'array' , items: new OA\Items(ref: new Model(type: City::class, groups: ['view']))),
                    new OA\Property(property: 'pagination', example: ["total" => 1, "lastPage" => 1, "page" => 1]),
                    new OA\Property(property: 'errors', example: []),
                ],
                type: 'object'
            )
        )
    )]
    public function showAll(
        EntityManagerInterface $entityManager,
        Request                $request
    ): JsonResponse
    {
        $dql = $entityManager->getRepository(City::class)->getQueryForAllCities();

        $page = $request->get('page', 1);
        $limit = $request->get('limit', 100);
        $query = $entityManager->createQuery($dql)
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit);

        $collection = new Paginator($query, fetchJoinCollection: true);

        return $this->response(data: $collection, pagination: $this->getPaginationTemplate($collection, $page, $limit), context: ['view']);
    }
}
