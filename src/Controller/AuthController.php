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

class AuthController extends BaseController
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    #[Route('/signup', name: 'sign_up', methods: ['POST'])]
    public function signUp(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher,
        JWTTokenManagerInterface $JWTManager
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


}
