<?php

namespace App\Controller;

use App\Entity\MenuItem;
use App\Form\KindMenuType;
use App\Form\MenuItemType;
use App\Service\MenuItemService;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Attributes as OA;

#[Route('/menu-item')]
#[OA\Tag(name: 'MenuItem')]
class MenuItemController extends BaseController
{

    public function __construct(
        public MenuItemService $menuItemService
    )
    {
    }

    #[Route(name: 'create_menu_item', methods: ['POST'])]
    #[OA\Post(summary: 'Create new MenuItem')]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: new Model(type: MenuItemType::class)))]
    #[OA\Response(
        response: 200,
        description: 'Create new MenuItem',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(
                properties: [
                    new OA\Property(property: 'data', ref: new Model(type: MenuItem::class, groups: ['view'])),
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
        $menuItem = new MenuItem();
        $form = $this->createForm(MenuItemType::class, $menuItem);
        $form->submit(json_decode($request->getContent(), true));

        if ($form->isSubmitted() && $form->isValid()) {
            $zone = $form->getData();
            $this->menuItemService->create($menuItem);

            return $this->response(data: $menuItem, context: ['view']);
        }

        return $this->response(errors: $this->getErrorsFromForm($form), status: 401);
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    #[Route('/{id}', name: 'update_menu_item', methods: ['PATCH'])]
    #[OA\Patch(summary: 'Update existed MenuItem')]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: new Model(type: MenuItemType::class)))]
    #[OA\Response(
        response: 200,
        description: 'Update existed MenuItem',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(
                properties: [
                    new OA\Property(property: 'data', ref: new Model(type: MenuItem::class, groups: ['view'])),
                    new OA\Property(property: 'pagination', example: []),
                    new OA\Property(property: 'errors', example: []),
                ],
                type: 'object'
            )
        )
    )]
    public function update(
        MenuItem $menuItem,
        Request $request
    ): JsonResponse
    {
        $oldTranslations = clone $menuItem->getTranslations();
        $form = $this->createForm(MenuItemType::class, $menuItem, ['method' => 'PUT']);
        $form->submit(json_decode($request->getContent(), true), false);

        if ($form->isValid()) {
            $zone = $form->getData();
            $this->menuItemService->update($menuItem, $oldTranslations);

            return $this->response(data: $menuItem, context: ['view']);
        }

        return $this->response(errors: $this->getErrorsFromForm($form), status: 401);
    }

    #[Route(name: 'show_all_menu_items', methods: ['GET'])]
    #[OA\Get(summary: 'Get paginated list of MenuItems', parameters: [
        new OA\Parameter(name: 'page', in: 'query', required: false, schema: new OA\Schema(type: 'int', example: 1)),
        new OA\Parameter(name: 'limit', in: 'query', required: false, schema: new OA\Schema(type: 'int', example: 1))
    ])]
    #[OA\Response(
        response: 200,
        description: 'Get paginated list of MenuItems',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(
                properties: [
                    new OA\Property(property: 'data', type: 'array' , items: new OA\Items(ref: new Model(type: MenuItem::class, groups: ['view']))),
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
        $dql = $entityManager->getRepository(MenuItem::class)->getQueryForAllMenuItems();

        $page = $request->get('page', 1);
        $limit = $request->get('limit', self::PAGE_LIMIT);
        $query = $entityManager->createQuery($dql)
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit);

        $collection = new Paginator($query, fetchJoinCollection: true);

        return $this->response(data: $collection, pagination: $this->getPaginationTemplate($collection, $page, $limit), context: ['view']);
    }

    #[Route('/{id}', name: 'show_one_menu_item', methods: ['GET'])]
    #[OA\Get(summary: 'Get MenuItem by id')]
    #[OA\Response(
        response: 200,
        description: 'Get MenuItem by id',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(
                properties: [
                    new OA\Property(property: 'data', ref: new Model(type: MenuItem::class, groups: ['view'])),
                    new OA\Property(property: 'pagination', example: []),
                    new OA\Property(property: 'errors', example: []),
                ],
                type: 'object'
            )
        )
    )]
    public function showOne(
        MenuItem $menuItem
    )
    {
        return $this->response(data: $menuItem, context: ['view']);
    }

    #[Route('/{id}', name: 'delete_menu_item', methods: ['DELETE'])]
    #[OA\Delete(summary: 'Delete MenuItem by id')]
    #[OA\Response(
        response: 200,
        description: 'Delete MenuItem by id',
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
        MenuItem $menuItem,
        EntityManagerInterface $entityManager
    ): JsonResponse
    {
        $entityManager->remove($menuItem);
        $entityManager->flush();

        return $this->response();
    }
}
