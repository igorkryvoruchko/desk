<?php

namespace App\Controller;

use App\Entity\Zone;
use App\Form\ZoneType;
use App\Service\ZoneService;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Attributes as OA;

#[Route('/zone')]
#[OA\Tag(name: 'Zone')]
class ZoneController extends BaseController
{

    public function __construct(
        public ZoneService $zoneService
    )
    {
    }

    #[Route(name: 'create_zone', methods: ['POST'])]
    #[OA\Post(summary: 'Create new Zone')]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: new Model(type: ZoneType::class)))]
    #[OA\Response(
        response: 200,
        description: 'Create new Zone',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(
                properties: [
                    new OA\Property(property: 'data', ref: new Model(type: Zone::class, groups: ['view'])),
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
        $zone = new Zone();
        $form = $this->createForm(ZoneType::class, $zone);
        $form->submit(json_decode($request->getContent(), true));

        if ($form->isSubmitted() && $form->isValid()) {
            $zone = $form->getData();
            $this->zoneService->create($zone);

            return $this->response(data: $zone, context: ['view']);
        }

        return $this->response(errors: $this->getErrorsFromForm($form), status: 401);
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    #[Route('/{id}', name: 'update_zone', methods: ['PATCH'])]
    #[OA\Patch(summary: 'Update existed Zone')]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: new Model(type: ZoneType::class)))]
    #[OA\Response(
        response: 200,
        description: 'Update existed Zone',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(
                properties: [
                    new OA\Property(property: 'data', ref: new Model(type: Zone::class, groups: ['view'])),
                    new OA\Property(property: 'pagination', example: []),
                    new OA\Property(property: 'errors', example: []),
                ],
                type: 'object'
            )
        )
    )]
    public function update(
        Zone $zone,
        Request $request
    ): JsonResponse
    {
        $oldTranslations = clone $zone->getTranslations();
        $form = $this->createForm(ZoneType::class, $zone, ['method' => 'PUT']);
        $form->submit(json_decode($request->getContent(), true), false);

        if ($form->isValid()) {
            $zone = $form->getData();
            $this->zoneService->update($zone, $oldTranslations);

            return $this->response(data: $zone, context: ['view']);
        }

        return $this->response(errors: $this->getErrorsFromForm($form), status: 401);
    }

    #[Route(name: 'show_all_zone', methods: ['GET'])]
    #[OA\Get(summary: 'Get paginated list of Zones', parameters: [
        new OA\Parameter(name: 'page', in: 'query', required: false, schema: new OA\Schema(type: 'int', example: 1)),
        new OA\Parameter(name: 'limit', in: 'query', required: false, schema: new OA\Schema(type: 'int', example: 1))
    ])]
    #[OA\Response(
        response: 200,
        description: 'Get paginated list of Zones',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(
                properties: [
                    new OA\Property(property: 'data', type: 'array' , items: new OA\Items(ref: new Model(type: Zone::class, groups: ['view']))),
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
        $dql = $entityManager->getRepository(Zone::class)->getQueryForAllZones();

        $page = $request->get('page', 1);
        $limit = $request->get('limit', self::PAGE_LIMIT);
        $query = $entityManager->createQuery($dql)
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit);

        $collection = new Paginator($query, fetchJoinCollection: true);

        return $this->response(data: $collection, pagination: $this->getPaginationTemplate($collection, $page, $limit), context: ['view']);
    }

    #[Route('/{id}', name: 'show_one_zone', methods: ['GET'])]
    #[OA\Get(summary: 'Get Zone by id')]
    #[OA\Response(
        response: 200,
        description: 'Get Zone by id',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(
                properties: [
                    new OA\Property(property: 'data', ref: new Model(type: Zone::class, groups: ['view'])),
                    new OA\Property(property: 'pagination', example: []),
                    new OA\Property(property: 'errors', example: []),
                ],
                type: 'object'
            )
        )
    )]
    public function showOne(
        Zone $zone
    )
    {
        return $this->response(data: $zone, context: ['view']);
    }

    #[Route('/{id}', name: 'delete_zone', methods: ['DELETE'])]
    #[OA\Delete(summary: 'Delete Zone by id')]
    #[OA\Response(
        response: 200,
        description: 'Delete Zone by id',
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
        Zone $zone,
        EntityManagerInterface $entityManager
    ): JsonResponse
    {
        $entityManager->remove($zone);
        $entityManager->flush();

        return $this->response();
    }
}
