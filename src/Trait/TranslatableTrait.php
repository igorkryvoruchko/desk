<?php

declare(strict_types=1);

namespace App\Trait;

use App\Entity\Contract\TranslationInterface;
use Doctrine\Common\Collections\Collection;


trait TranslatableTrait
{
    /**
     * @return Collection<int, TranslationInterface>
     */
    public function getTranslations(): Collection
    {   
        return $this->translations;
    }

    public function addTranslation(TranslationInterface $translation): static
    {
        if (!$this->translations->contains($translation)) {
            $this->translations->add($translation);
            $translation->setTranslatable($this);
        }

        return $this;
    }

    public function removeTranslation(TranslationInterface $translation): static
    {
        if ($this->translations->removeElement($translation)) {
            // set the owning side to null (unless already changed)
            if ($translation->getTranslatable() === $this) {
                $translation->setTranslatable(null);
            }
        }

        return $this;
    }
}
