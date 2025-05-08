<?php

namespace App\Entity\Contract;

use Doctrine\Common\Collections\Collection;

interface TranslatableInterface
{
    public function getTranslations(): Collection;
    public function addTranslation(TranslationInterface $translation): static;
    public function removeTranslation(TranslationInterface $translation): static;
}