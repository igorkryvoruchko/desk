<?php

namespace App\Entity\Contract;

use Doctrine\ORM\Mapping as ORM;

/**
 * Interface SoftDeletableInterface
 *
 * @package App\Entity\Contract
 */
interface SoftDeletableInterface
{
    /**
     * Get the date when the entity was soft deleted.
     *
     * @return \DateTimeInterface|null
     */
    public function getDeletedAt(): ?\DateTimeInterface;

    /**
     * Set the date when the entity was soft deleted.
     *
     * @param \DateTimeInterface|null $deletedAt
     */
    public function setDeletedAt(?\DateTimeInterface $deletedAt): void;
}