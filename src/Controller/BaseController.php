<?php

namespace App\Controller;

use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;

class BaseController extends AbstractController
{
    const PAGE_LIMIT = 10;
    protected function response(
        mixed $data = [],
        array $errors = [],
        array $pagination = [],
        int $status = 200,
        array $headers = [],
        array $context = []
    ): JsonResponse
    {
        return $this->json(
            data: [
                'data' => $data,
                'pagination' => $pagination,
                'errors' => $errors,
            ],
            status: $status,
            headers: $headers,
            context: count($context) > 0 ? (new ObjectNormalizerContextBuilder())->withGroups($context)->toArray() : []);
    }

    protected function getPaginationTemplate(Paginator $collection, int $page, int $limit): array
    {
        return [
            "total" => $collection->count(),
            "lastPage" => (int) ceil($collection->count() / $limit),
            "page" => $page
        ];
    }
    protected function getErrorsFromForm(FormInterface $form): array
    {
        $errors = array();
        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }

        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                if ($childErrors = $this->getErrorsFromForm($childForm)) {
                    $errors[$childForm->getName()] = $childErrors;
                }
            }
        }

        return $errors;
    }
}
