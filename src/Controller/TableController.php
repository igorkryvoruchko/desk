<?php

namespace App\Controller;

use App\Entity\Table;
use App\Form\TableType;
use App\Service\TableService;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Attributes as OA;

#[Route('/table')]
#[OA\Tag(name: 'Table')]
class TableController extends BaseController
{

    public function __construct(
        public TableService $tableService
    )
    {
    }

    #[Route(name: 'create_table', methods: ['POST'])]
    #[OA\Post(summary: 'Create new Table')]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: new Model(type: TableType::class)))]
    #[OA\Response(
        response: 200,
        description: 'Create new Table',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(
                properties: [
                    new OA\Property(property: 'data', ref: new Model(type: Table::class, groups: ['view'])),
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
        $table = new Table();
        $form = $this->createForm(TableType::class, $table);
        $form->submit(json_decode($request->getContent(), true));

        if ($form->isSubmitted() && $form->isValid()) {
            $table = $form->getData();
            $this->tableService->create($table);

            return $this->response(data: $table, context: ['view']);
        }

        return $this->response(errors: $this->getErrorsFromForm($form), status: 401);
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    #[Route('/{id}', name: 'update_table', methods: ['PATCH'])]
    #[OA\Patch(summary: 'Update existed Table')]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: new Model(type: TableType::class)))]
    #[OA\Response(
        response: 200,
        description: 'Update existed Table',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(
                properties: [
                    new OA\Property(property: 'data', ref: new Model(type: Table::class, groups: ['view'])),
                    new OA\Property(property: 'pagination', example: []),
                    new OA\Property(property: 'errors', example: []),
                ],
                type: 'object'
            )
        )
    )]
    public function update(
        Table $table,
        Request $request,
        EntityManagerInterface $entityManager
    ): JsonResponse
    {
        $form = $this->createForm(TableType::class, $table, ['method' => 'PUT']);
        $form->submit(json_decode($request->getContent(), true), false);

        if ($form->isValid()) {
            $table = $form->getData();
            $entityManager->persist($table);
            $entityManager->flush();

            return $this->response(data: $table, context: ['view']);
        }

        return $this->response(errors: $this->getErrorsFromForm($form), status: 401);
    }

    #[Route(name: 'show_all_table', methods: ['GET'])]
    #[OA\Get(summary: 'Get paginated list of Tables', parameters: [
        new OA\Parameter(name: 'page', in: 'query', required: false, schema: new OA\Schema(type: 'int', example: 1)),
        new OA\Parameter(name: 'limit', in: 'query', required: false, schema: new OA\Schema(type: 'int', example: 1))
    ])]
    #[OA\Response(
        response: 200,
        description: 'Get paginated list of Tables',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(
                properties: [
                    new OA\Property(property: 'data', type: 'array' , items: new OA\Items(ref: new Model(type: Table::class, groups: ['view']))),
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
        $dql = $entityManager->getRepository(Table::class)->getQueryForAllTables();

        $page = $request->get('page', 1);
        $limit = $request->get('limit', self::PAGE_LIMIT);
        $query = $entityManager->createQuery($dql)
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit);

        $collection = new Paginator($query, fetchJoinCollection: true);

        return $this->response(data: $collection, pagination: $this->getPaginationTemplate($collection, $page, $limit), context: ['view']);
    }

    #[Route('/{id}', name: 'show_one_table', methods: ['GET'])]
    #[OA\Get(summary: 'Get Table by id')]
    #[OA\Response(
        response: 200,
        description: 'Get Table by id',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(
                properties: [
                    new OA\Property(property: 'data', ref: new Model(type: Table::class, groups: ['view'])),
                    new OA\Property(property: 'pagination', example: []),
                    new OA\Property(property: 'errors', example: []),
                ],
                type: 'object'
            )
        )
    )]
    public function showOne(
        Table $table
    )
    {
        return $this->response(data: $table, context: ['view']);
    }

    #[Route('/{id}', name: 'delete_table', methods: ['DELETE'])]
    #[OA\Delete(summary: 'Delete Table by id')]
    #[OA\Response(
        response: 200,
        description: 'Delete Table by id',
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
        Table $table,
        EntityManagerInterface $entityManager
    ): JsonResponse
    {
        $entityManager->remove($table);
        $entityManager->flush();

        return $this->response();
    }
}
