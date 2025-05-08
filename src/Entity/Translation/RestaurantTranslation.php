<?php

namespace App\Entity\Translation;

use App\Trait\TranslationTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Contract\TranslationInterface;
use App\Entity\Restaurant;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
class RestaurantTranslation implements TranslationInterface
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
    private ?Restaurant $translatable = null;

    #[ORM\Column(length: 255)]
    #[Groups(['view'])]
    private ?string $description = null;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }
}
