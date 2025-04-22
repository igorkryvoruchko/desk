<?php

namespace App\Controller;

use App\Entity\KindMenu;
use App\Form\KindMenuType;
use App\Service\KindMenuService;
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

#[Route('/kind-menu')]
#[OA\Tag(name: 'KindMenu')]
class KindMenuController extends BaseController
{

    public function __construct(
        public KindMenuService $kindMenuService
    )
    {
    }

    #[Route(name: 'create_kind_menu', methods: ['POST'])]
    #[OA\Post(summary: 'Create new KindMenu')]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: new Model(type: KindMenuType::class)))]
    #[OA\Response(
        response: Response::HTTP_CREATED,
        description: 'Create new KindMenu',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(
                properties: [
                    new OA\Property(property: 'data', ref: new Model(type: KindMenu::class, groups: ['view'])),
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
        $kindMenu = new KindMenu();
        $form = $this->createForm(KindMenuType::class, $kindMenu);
        $form->submit(json_decode($request->getContent(), true));

        if ($form->isSubmitted() && $form->isValid()) {
            $zone = $form->getData();
            $this->kindMenuService->create($zone);

            return $this->response(data: $kindMenu, context: ['view'], status: Response::HTTP_CREATED);
        }

        return $this->response(errors: $this->getErrorsFromForm($form), status: Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    #[Route('/{id}', name: 'update_kind_menu', methods: ['PATCH'])]
    #[OA\Patch(summary: 'Update existed KindMenu')]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: new Model(type: KindMenuType::class)))]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Update existed KindMenu',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(
                properties: [
                    new OA\Property(property: 'data', ref: new Model(type: KindMenu::class, groups: ['view'])),
                    new OA\Property(property: 'pagination', example: []),
                    new OA\Property(property: 'errors', example: []),
                ],
                type: 'object'
            )
        )
    )]
    public function update(
        KindMenu $kindMenu,
        Request $request
    ): JsonResponse
    {
        $oldTranslations = clone $kindMenu->getTranslations();
        $form = $this->createForm(KindMenuType::class, $kindMenu, ['method' => 'PUT']);
        $form->submit(json_decode($request->getContent(), true), false);

        if ($form->isValid()) {
            $zone = $form->getData();
            $this->kindMenuService->update($kindMenu, $oldTranslations);

            return $this->response(data: $kindMenu, context: ['view']);
        }

        return $this->response(errors: $this->getErrorsFromForm($form), status: Response::HTTP_UNAUTHORIZED);
    }

    #[Route(name: 'show_all_kind_menus', methods: ['GET'])]
    #[OA\Get(summary: 'Get paginated list of KindMenus', parameters: [
        new OA\Parameter(name: 'page', in: 'query', required: false, schema: new OA\Schema(type: 'int', example: 1)),
        new OA\Parameter(name: 'limit', in: 'query', required: false, schema: new OA\Schema(type: 'int', example: 1))
    ])]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Get paginated list of KindMenus',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(
                properties: [
                    new OA\Property(property: 'data', type: 'array' , items: new OA\Items(ref: new Model(type: KindMenu::class, groups: ['view']))),
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
        $dql = $entityManager->getRepository(KindMenu::class)->getQueryForAllKindMenus();

        $page = $request->get('page', 1);
        $limit = $request->get('limit', self::PAGE_LIMIT);
        $query = $entityManager->createQuery($dql)
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit);

        $collection = new Paginator($query, fetchJoinCollection: true);

        return $this->response(data: $collection, pagination: $this->getPaginationTemplate($collection, $page, $limit), context: ['view']);
    }

    #[Route('/{id}', name: 'show_one_kind_menu', methods: ['GET'])]
    #[OA\Get(summary: 'Get KindMenu by id')]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Get KindMenu by id',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(
                properties: [
                    new OA\Property(property: 'data', ref: new Model(type: KindMenu::class, groups: ['view'])),
                    new OA\Property(property: 'pagination', example: []),
                    new OA\Property(property: 'errors', example: []),
                ],
                type: 'object'
            )
        )
    )]
    public function showOne(
        KindMenu $kindMenu
    )
    {
        return $this->response(data: $kindMenu, context: ['view']);
    }

    #[Route('/{id}', name: 'delete_kind_menu', methods: ['DELETE'])]
    #[OA\Delete(summary: 'Delete KindMenu by id')]
    #[OA\Response(
        response: Response::HTTP_NO_CONTENT,
        description: 'Delete KindMenu by id',
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
        KindMenu $kindMenu,
        EntityManagerInterface $entityManager
    ): JsonResponse
    {
        $entityManager->remove($kindMenu);
        $entityManager->flush();

        return $this->response(status: Response::HTTP_NO_CONTENT);
    }
}
