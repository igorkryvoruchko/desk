<?php

declare(strict_types=1);

namespace App\Trait;

use App\Entity\Contract\TranslatableInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[UniqueEntity(
    fields: ['locale', 'translatable'],
    message: 'This locale is already in use on that company.',
    errorPath: 'locale',
)]
trait TranslationTrait
{
    #[ORM\Column(length: 255)]
    #[Groups(['view'])]
    private ?string $locale = null;

    public function getLocale(): ?string
    {
        return $this->locale;
    }

    public function setLocale(string $locale): static
    {
        $this->locale = $locale;

        return $this;
    }

    public function getTranslatable(): ?TranslatableInterface
    {
        return $this->translatable;
    }

    public function setTranslatable(?TranslatableInterface $translatable): static
    {
        $this->translatable = $translatable;

        return $this;
    }
}