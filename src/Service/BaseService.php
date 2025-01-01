<?php

namespace App\Service;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use Symfony\Component\Serializer\SerializerInterface;
class BaseService
{

    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected SerializerInterface $serializer
    )
    {
    }

    public function create(TranslatableInterface $entity): void
    {
        $this->entityManager->persist($entity);
        $entity->mergeNewTranslations();
        $this->entityManager->flush();
    }

    
    public function update(TranslatableInterface $entity, Collection $oldTranslations): void
    {
        $entity = $this->deleteTranslations(
            $entity,
            $oldTranslations
        );

        $this->entityManager->persist($entity);
        $entity->mergeNewTranslations();

        $this->entityManager->flush();
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    protected function deleteTranslations(TranslatableInterface $entity, Collection $oldTranslations): TranslatableInterface
    {
        $translations = $entity->getTranslations();

        foreach ($oldTranslations as $oldTranslation) {
            foreach ($translations as $translation) {
                if ($translation->getLocale() == $oldTranslation->getLocale()) {
                    $this->entityManager->remove($oldTranslation);
                    $this->entityManager->flush($oldTranslation);
                }
            }
        }

        return $entity;
    }
}