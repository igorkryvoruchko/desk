<?php

namespace App\Controller;

use App\Entity\Company;
use App\Form\CompanyType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/company')]
class CompanyController extends BaseController
{
    #[Route(name: 'create_company', methods: ['POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $entityManager,
    ): JsonResponse
    {
        $company = new Company();
        $form = $this->createForm(CompanyType::class, $company);
        $form->submit(json_decode($request->getContent(), true));

        if ($form->isValid()) {
            $company = $form->getData();
            $entityManager->persist($company);
            $company->mergeNewTranslations();
            $entityManager->flush();

            return $this->response(data: $company, context: ['view']);
        }

        return $this->response(errors: $this->getErrorsFromForm($form), status: 401);
    }

    #[Route('/{id}', name: 'update_company', methods: ['PATCH'])]
    public function update(
        Company $company,
        Request $request,
        EntityManagerInterface $entityManager
    )
    {
        $form = $this->createForm(CompanyType::class, $company, ['method' => 'PUT']);
        $form->submit(json_decode($request->getContent(), true), false);

        if ($form->isValid()) {
            $company = $form->getData();
            $company->mergeNewTranslations();
            $entityManager->flush();

            return $this->response(data: $company, context: ['view']);
        }

        return $this->response(errors: $this->getErrorsFromForm($form), status: 401);
    }

    #[Route(name: 'show_all_company', methods: ['GET'])]
    public function showAll(
        EntityManagerInterface $entityManager
    )
    {
        $companies = $entityManager->getRepository(Company::class)->findAll();

        return $this->response(data: $companies, context: ['view']);
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
    )
    {
        $entityManager->remove($company);
        $entityManager->flush();

        return $this->response();
    }
}
