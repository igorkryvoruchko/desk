<?php

namespace App\Service;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;

class BaseService
{

    public function __construct(
        protected EntityManagerInterface $entityManager
    )
    {
    }

    public function create($entity): void
    {
        $this->entityManager->persist($entity);
        $entity->mergeNewTranslations();
        $this->entityManager->flush();
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function update(TranslatableInterface $entity, Collection $oldTranslations): void
    {
        $translations = $entity->getTranslations();
        $this->deleteTranslations(
            $translations,
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
    protected function deleteTranslations(Collection $translations, Collection $oldTranslations): void
    {
        foreach ($oldTranslations as $oldTranslation) {
            foreach ($translations as $translation) {
                if ($translation->getId() == null && $translation->getLocale() == $oldTranslation->getLocale()) {
                    $this->entityManager->remove($oldTranslation);
                    $this->entityManager->flush($oldTranslation);
                }
            }
        }
    }
}