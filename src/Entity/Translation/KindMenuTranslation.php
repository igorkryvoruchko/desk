<?php

namespace App\Entity\Translation;

use App\Trait\TranslationDirectionTrait;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TranslationInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
class KindMenuTranslation implements TranslationInterface
{
    use TranslationDirectionTrait;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['view'])]
    private ?string $name = null;

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
