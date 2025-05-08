<?php

namespace App\Entity\Translation;

use App\Trait\TranslationTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Contract\TranslationInterface;
use App\Entity\Zone;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
class ZoneTranslation implements TranslationInterface
{
    use TranslationTrait;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['view'])]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'translations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Zone $translatable = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }
}
