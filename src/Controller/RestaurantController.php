<?php

namespace App\Controller;

use App\Entity\Restaurant;
use App\Form\RestaurantType;
use App\Service\RestaurantService;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Attributes as OA;

#[Route('/restaurant')]
#[OA\Tag(name: 'Restaurant')]
class RestaurantController extends BaseController
{

    public function __construct(
        public RestaurantService $restaurantService
    )
    {
    }

    #[Route(name: 'create_restaurant', methods: ['POST'])]
    #[OA\Post(summary: 'Create new Restaurant')]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: new Model(type: RestaurantType::class)))]
    #[OA\Response(
        response: 200,
        description: 'Create new Restaurant',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(
                properties: [
                    new OA\Property(property: 'data', ref: new Model(type: Restaurant::class, groups: ['view'])),
                    new OA\Property(property: 'pagination', example: []),
                    new OA\Property(property: 'errors', example: []),
                ],
                type: 'object'
            )
        )
    )]
    public function create(
        Request $request,
    ): JsonResponse
    {
        $restaurant = new Restaurant();
        $form = $this->createForm(RestaurantType::class, $restaurant);
        $form->submit(json_decode($request->getContent(), true));

        if ($form->isValid()) {
            $restaurant = $form->getData();
            $this->restaurantService->create($restaurant);

            return $this->response(data: $restaurant, context: ['view']);
        }

        return $this->response(errors: $this->getErrorsFromForm($form), status: 401);
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    #[Route('/{id}', name: 'update_restaurant', methods: ['PATCH'])]
    #[OA\Patch(summary: 'Update existed Restaurant')]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: new Model(type: RestaurantType::class)))]
    #[OA\Response(
        response: 200,
        description: 'Update existed Restaurant',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(
                properties: [
                    new OA\Property(property: 'data', ref: new Model(type: Restaurant::class, groups: ['view'])),
                    new OA\Property(property: 'pagination', example: []),
                    new OA\Property(property: 'errors', example: []),
                ],
                type: 'object'
            )
        )
    )]
    public function update(
        Restaurant $restaurant,
        Request $request
    ): JsonResponse
    {
        $oldTranslations = clone $restaurant->getTranslations();
        $form = $this->createForm(RestaurantType::class, $restaurant, ['method' => 'PUT']);
        $form->submit(json_decode($request->getContent(), true), false);

        if ($form->isValid()) {
            $restaurant = $form->getData();
            $this->restaurantService->update($restaurant, $oldTranslations);

            return $this->response(data: $restaurant, context: ['view']);
        }

        return $this->response(errors: $this->getErrorsFromForm($form), status: 401);
    }

    #[Route(name: 'show_all_restaurant', methods: ['GET'])]
    #[OA\Get(summary: 'Get paginated list of Restaurants', parameters: [
        new OA\Parameter(name: 'page', in: 'query', required: false, schema: new OA\Schema(type: 'int', example: 1)),
        new OA\Parameter(name: 'limit', in: 'query', required: false, schema: new OA\Schema(type: 'int', example: 1))
    ])]
    #[OA\Response(
        response: 200,
        description: 'Get paginated list of Restaurants',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(
                properties: [
                    new OA\Property(property: 'data', type: 'array' , items: new OA\Items(ref: new Model(type: Restaurant::class, groups: ['view']))),
                    new OA\Property(property: 'pagination', example: ["total" => 1, "lastPage" => 1, "page" => 1]),
                    new OA\Property(property: 'errors', example: []),
                ],
                type: 'object'
            )
        )
    )]
    public function showAll(
        EntityManagerInterface $entityManager,
        Request $request
    ): JsonResponse
    {
        $dql = $entityManager->getRepository(Restaurant::class)->getQueryForAllRestaurant();

        $page = $request->get('page', 1);
        $limit = $request->get('limit', self::PAGE_LIMIT);
        $query = $entityManager->createQuery($dql)
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit);

        $collection = new Paginator($query, fetchJoinCollection: true);

        return $this->response(data: $collection, pagination: $this->getPaginationTemplate($collection, $page, $limit), context: ['view']);
    }

    #[Route('/{id}', name: 'show_one_restaurant', methods: ['GET'])]
    #[OA\Get(summary: 'Get Restaurant by id')]
    #[OA\Response(
        response: 200,
        description: 'Get Restaurant by id',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(
                properties: [
                    new OA\Property(property: 'data', ref: new Model(type: Restaurant::class, groups: ['view'])),
                    new OA\Property(property: 'pagination', example: []),
                    new OA\Property(property: 'errors', example: []),
                ],
                type: 'object'
            )
        )
    )]
    public function showOne(
        Restaurant $restaurant
    )
    {
        return $this->response(data: $restaurant, context: ['view']);
    }

    #[Route('/{id}', name: 'delete_restaurant', methods: ['DELETE'])]
    #[OA\Delete(summary: 'Delete Restaurant by id')]
    #[OA\Response(
        response: 200,
        description: 'Delete Restaurant by id',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(
                properties: [
                    new OA\Property(property: 'data', example: []),
                    new OA\Property(property: 'pagination', example: []),
                    new OA\Property(property: 'errors', example: []),
                ],
                type: 'object'
            )
        )
    )]
    public function delete(
        Restaurant $restaurant,
        EntityManagerInterface $entityManager
    ): JsonResponse
    {
        $entityManager->remove($restaurant);
        $entityManager->flush();

        return $this->response();
    }
}
