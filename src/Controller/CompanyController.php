<?php

namespace App\Controller;

use App\Entity\Company;
use App\Form\CompanyType;
use App\Service\CompanyService;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/company')]
class CompanyController extends BaseController
{

    public function __construct(
        public CompanyService $companyService
    )
    {
    }

    #[Route(name: 'create_company', methods: ['POST'])]
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
    public function showAll(
        EntityManagerInterface $entityManager,
        Request $request
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
    public function showOne(
        Company $company
    )
    {
        return $this->response(data: $company, context: ['view']);
    }

    #[Route('/{id}', name: 'delete_company', methods: ['DELETE'])]
    public function delete(
        Company $company,
        EntityManagerInterface $entityManager
    ): JsonResponse
    {
        $entityManager->remove($company);
        $entityManager->flush();

        return $this->response();
    }
}
