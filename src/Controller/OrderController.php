<?php

namespace App\Controller;

use App\Entity\Order;
use App\Form\OrderType;
use App\Service\OrderService;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Nelmio\ApiDocBundle\Attribute\Model;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;

#[Route('/order')]
#[OA\Tag(name: 'Order')]
class OrderController extends BaseController
{

    public function __construct(
        public OrderService $orderService
    )
    {
    }

    #[Route(name: 'create_order', methods: ['POST'])]
    #[OA\Post(summary: 'Create new Order')]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: new Model(type: OrderType::class)))]
    #[OA\Response(
        response: Response::HTTP_CREATED,
        description: 'Create new Order',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(
                properties: [
                    new OA\Property(property: 'data', ref: new Model(type: Order::class, groups: ['view'])),
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
        $order = new Order();
        $form = $this->createForm(OrderType::class, $order);
        $form->submit(json_decode($request->getContent(), true));

        if ($form->isSubmitted() && $form->isValid()) {
            $order = $form->getData();
            $this->orderService->saveOrder($order);

            return $this->response(data: $order, context: ['view'], status: Response::HTTP_CREATED);
        }

        return $this->response(errors: $this->getErrorsFromForm($form), status: Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    #[Route('/{id}', name: 'update_order', methods: ['PATCH'])]
    #[OA\Patch(summary: 'Update existed Order')]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: new Model(type: OrderType::class)))]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Update existed Order',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(
                properties: [
                    new OA\Property(property: 'data', ref: new Model(type: Order::class, groups: ['view'])),
                    new OA\Property(property: 'pagination', example: []),
                    new OA\Property(property: 'errors', example: []),
                ],
                type: 'object'
            )
        )
    )]
    public function update(
        Order $order,
        Request $request,
    ): JsonResponse
    {
        $form = $this->createForm(OrderType::class, $order, ['method' => 'PUT']);
        $form->submit(json_decode($request->getContent(), true), false);
        $this->orderService->saveOrder($order);
        if ($form->isValid()) {
            $order = $form->getData();
            

            return $this->response(data: $order, context: ['view']);
        }

        return $this->response(errors: $this->getErrorsFromForm($form), status: Response::HTTP_UNAUTHORIZED);
    }

    #[Route(name: 'show_all_order', methods: ['GET'])]
    #[OA\Get(summary: 'Get paginated list of Order', parameters: [
        new OA\Parameter(name: 'page', in: 'query', required: false, schema: new OA\Schema(type: 'int', example: 1)),
        new OA\Parameter(name: 'limit', in: 'query', required: false, schema: new OA\Schema(type: 'int', example: 1))
    ])]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Get paginated list of Orders',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(
                properties: [
                    new OA\Property(property: 'data', type: 'array' , items: new OA\Items(ref: new Model(type: Order::class, groups: ['view']))),
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
        $dql = $entityManager->getRepository(Order::class)->getQueryForAllOrders();

        $page = $request->get('page', 1);
        $limit = $request->get('limit', self::PAGE_LIMIT);
        $query = $entityManager->createQuery($dql)
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit);

        $collection = new Paginator($query, fetchJoinCollection: true);

        return $this->response(data: $collection, pagination: $this->getPaginationTemplate($collection, $page, $limit), context: ['view']);
    }

    #[Route('/{id}', name: 'show_one_order', methods: ['GET'])]
    #[OA\Get(summary: 'Get Order by id')]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Get Order by id',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(
                properties: [
                    new OA\Property(property: 'data', ref: new Model(type: Order::class, groups: ['view'])),
                    new OA\Property(property: 'pagination', example: []),
                    new OA\Property(property: 'errors', example: []),
                ],
                type: 'object'
            )
        )
    )]
    public function showOne(
        Order $order
    )
    {
        return $this->response(data: $order, context: ['view']);
    }

    #[Route('/{id}', name: 'delete_order', methods: ['DELETE'])]
    #[OA\Delete(summary: 'Delete Order by id')]
    #[OA\Response(
        response: Response::HTTP_NO_CONTENT,
        description: 'Delete Order by id',
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
        Order $order,
        EntityManagerInterface $entityManager
    ): JsonResponse
    {
        $entityManager->remove($order);
        $entityManager->flush();

        return $this->response(status: Response::HTTP_NO_CONTENT);
    }
}
