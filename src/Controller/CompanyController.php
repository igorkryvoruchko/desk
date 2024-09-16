<?php

namespace App\Controller;

use App\Entity\Company;
use App\Form\CompanyType;
use App\Service\CompanyService;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Attributes as OA;

#[Route('/company')]
#[OA\Tag(name: 'Company')]
class CompanyController extends BaseController
{

    public function __construct(
        public CompanyService $companyService
    )
    {
    }

    #[Route(name: 'create_company', methods: ['POST'])]
    #[OA\Post(summary: 'Create new Company')]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: new Model(type: CompanyType::class)))]
    #[OA\Response(
        response: 200,
        description: 'Create new Company',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(
                properties: [
                    new OA\Property(property: 'data', ref: new Model(type: Company::class, groups: ['view'])),
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
        $company = new Company();
        $form = $this->createForm(CompanyType::class, $company);
        $form->submit(json_decode($request->getContent(), true));

        if ($form->isValid()) {
            $company = $form->getData();
            $this->companyService->create($company);

            return $this->response(data: $company, context: ['view']);
        }

        return $this->response(errors: $this->getErrorsFromForm($form), status: 401);
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    #[Route('/{id}', name: 'update_company', methods: ['PATCH'])]
    #[OA\Patch(summary: 'Update existed Company')]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: new Model(type: CompanyType::class)))]
    #[OA\Response(
        response: 200,
        description: 'Update existed Company',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(
                properties: [
                    new OA\Property(property: 'data', ref: new Model(type: Company::class, groups: ['view'])),
                    new OA\Property(property: 'pagination', example: []),
                    new OA\Property(property: 'errors', example: []),
                ],
                type: 'object'
            )
        )
    )]
    public function update(
        Company $company,
        Request $request
    ): JsonResponse
    {
        $oldTranslations = clone $company->getTranslations();
        $form = $this->createForm(CompanyType::class, $company, ['method' => 'PUT']);
        $form->submit(json_decode($request->getContent(), true), false);

        if ($form->isValid()) {
            $company = $form->getData();
            $this->companyService->update($company, $oldTranslations);

            return $this->response(data: $company, context: ['view']);
        }

        return $this->response(errors: $this->getErrorsFromForm($form), status: 401);
    }

    #[Route(name: 'show_all_company', methods: ['GET'])]
    #[OA\Get(summary: 'Get paginated list of Companies', parameters: [
            new OA\Parameter(name: 'page', in: 'query', required: false, schema: new OA\Schema(type: 'int', example: 1)),
            new OA\Parameter(name: 'limit', in: 'query', required: false, schema: new OA\Schema(type: 'int', example: 1))
    ])]
    #[OA\Response(
        response: 200,
        description: 'Get paginated list of Companies',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(
                properties: [
                    new OA\Property(property: 'data', type: 'array' , items: new OA\Items(ref: new Model(type: Company::class, groups: ['view']))),
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
        $dql = $entityManager->getRepository(Company::class)->getQueryForAllCompanies();

        $page = $request->get('page', 1);
        $limit = $request->get('limit', self::PAGE_LIMIT);
        $query = $entityManager->createQuery($dql)
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit);

        $collection = new Paginator($query, fetchJoinCollection: true);

        return $this->response(data: $collection, pagination: $this->getPaginationTemplate($collection, $page, $limit), context: ['view']);
    }

    #[Route('/{id}', name: 'show_one_company', methods: ['GET'])]
    #[OA\Get(summary: 'Get Company by id')]
    #[OA\Response(
        response: 200,
        description: 'Get Company by id',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(
                properties: [
                    new OA\Property(property: 'data', ref: new Model(type: Company::class, groups: ['view'])),
                    new OA\Property(property: 'pagination', example: []),
                    new OA\Property(property: 'errors', example: []),
                ],
                type: 'object'
            )
        )
    )]
    public function showOne(
        Company $company
    )
    {
        return $this->response(data: $company, context: ['view']);
    }

    #[Route('/{id}', name: 'delete_company', methods: ['DELETE'])]
    #[OA\Delete(summary: 'Delete Company by id')]
    #[OA\Response(
        response: 200,
        description: 'Delete Company by id',
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
        Company                $company,
        EntityManagerInterface $entityManager
    ): JsonResponse
    {
        $entityManager->remove($company);
        $entityManager->flush();

        return $this->response();
    }
}
