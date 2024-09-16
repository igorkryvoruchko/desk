<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AuthSignUpType;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Auth')]
class AuthController extends BaseController
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    #[Route('/signup', name: 'sign_up', methods: ['POST'])]
    #[OA\Post(summary: 'Register new User')]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            ref: new Model(type: AuthSignUpType::class)
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Register new users',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(
                properties: [
                    new OA\Property(property: 'data', type: 'array', items: new OA\Items(properties: [
                        new OA\Property(property: 'user', ref: new Model(type: User::class, groups: ['view'])),
                        new OA\Property(property: 'token', example: "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE3MjY0MzA4MDIsImV4cCI6MTczMDAzMDgwMiwicm9sZXMiOlsiUk9MRV9VU0VSIl0sInVzZXJuYW1lIjoiaWdvcjEzN0BnbWFpbC5jb20ifQ.d4m4yBqIPbstACNVKv_nreG1Sw76WK5EMT7JCAcOGcdsaO4RH73yvVkw4UBWPVr2KmhQjjzPr3yBXE6xGgOHSnDIVPbivindthdftxJpRhD8i2zCF2nDXniruwkJWQeGRM_emK3q80i06f1d_pxAeC15ztWSd8VtRxuWOBbmoggXnD5nPhLtVhqfZ4nheOS9kI8_dZhV5gBliW-_v_BItw-3FVxV5_ylq1pN47_ZYQH6mmJZDT054-3f-07ZD1ha_FOEbHbvbd2tRbel-ttJt40IUx_Kn7FRm-teQNC9hfJDUMfGi_WQiEg0HnPGBYaYjbh3AqxtgAUCOvA7KEJOQw"),
                    ])),
                    new OA\Property(property: 'pagination', example: []),
                    new OA\Property(property: 'errors', example: []),
                ],
                type: 'object'
            )
        )
    )]
    public function signUp(
        Request                     $request,
        EntityManagerInterface      $entityManager,
        UserPasswordHasherInterface $passwordHasher,
        JWTTokenManagerInterface    $JWTManager
    ): JsonResponse
    {

        $user = new User();
        $form = $this->createForm(AuthSignUpType::class, $user);
        $form->submit(json_decode($request->getContent(), true));

        if ($form->isValid()) {
            $user = $form->getData();
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $user->getPassword()
            );
            $user->setPassword($hashedPassword);
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->response(data: [
                'user' => $user,
                'token' => $JWTManager->create($user)
            ], context: ['view']);
        }

        return $this->response(errors: $this->getErrorsFromForm($form), status: 401);
    }

    #[Route('/login_check', name: 'api_login_check', methods: ['POST'])]
    #[OA\Post(summary: 'SignIn')]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'email', example: "John@gmail.com"),
                new OA\Property(property: 'password', example: "jhDSw5HR#1"),
            ],
            type: 'object',
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Register new users',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(
                properties: [
                    new OA\Property(property: 'data', type: 'array', items: new OA\Items(properties: [
                        new OA\Property(property: 'user', ref: new Model(type: User::class, groups: ['view'])),
                        new OA\Property(property: 'token', example: "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE3MjY0MzA4MDIsImV4cCI6MTczMDAzMDgwMiwicm9sZXMiOlsiUk9MRV9VU0VSIl0sInVzZXJuYW1lIjoiaWdvcjEzN0BnbWFpbC5jb20ifQ.d4m4yBqIPbstACNVKv_nreG1Sw76WK5EMT7JCAcOGcdsaO4RH73yvVkw4UBWPVr2KmhQjjzPr3yBXE6xGgOHSnDIVPbivindthdftxJpRhD8i2zCF2nDXniruwkJWQeGRM_emK3q80i06f1d_pxAeC15ztWSd8VtRxuWOBbmoggXnD5nPhLtVhqfZ4nheOS9kI8_dZhV5gBliW-_v_BItw-3FVxV5_ylq1pN47_ZYQH6mmJZDT054-3f-07ZD1ha_FOEbHbvbd2tRbel-ttJt40IUx_Kn7FRm-teQNC9hfJDUMfGi_WQiEg0HnPGBYaYjbh3AqxtgAUCOvA7KEJOQw"),
                    ])),
                    new OA\Property(property: 'pagination', example: []),
                    new OA\Property(property: 'errors', example: []),
                ],
                type: 'object'
            )
        )
    )]
    public function apiLogin()
    {
    }
}
