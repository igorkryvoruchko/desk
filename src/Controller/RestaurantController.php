<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\Restaurant;
use App\Form\RestaurantType;
use App\Service\RestaurantService;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/restaurant')]
class RestaurantController extends BaseController
{

    public function __construct(
        public RestaurantService $restaurantService
    )
    {
    }

    #[Route(name: 'create_restaurant', methods: ['POST'])]
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
    public function showOne(
        Restaurant $restaurant
    )
    {
        return $this->response(data: $restaurant, context: ['view']);
    }

    #[Route('/{id}', name: 'delete_restaurant', methods: ['DELETE'])]
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
