<?php

namespace App\Entity\Contract;

interface TranslationInterface
{
    public function getId(): ?int;

    public function getLocale(): ?string;

    public function setLocale(string $locale): static;

    public function getTranslatable(): ?TranslatableInterface;

    public function setTranslatable(?TranslatableInterface $translatable): static;
}