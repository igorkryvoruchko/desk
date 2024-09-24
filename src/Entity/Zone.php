<?php

namespace App\Entity;

use App\Repository\ZoneRepository;
use App\Trait\TranslatableDirectionTrait;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ZoneRepository::class)]
class Zone implements TranslatableInterface
{
    use TranslatableDirectionTrait;

    #[Groups(['view'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['view'])]
    #[ORM\Column(length: 255)]
    private ?string $alias = null;

    #[Groups(['view'])]
    #[ORM\ManyToOne(inversedBy: 'zones')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Restaurant $restaurant = null;

    #[Groups(['view'])]
    protected $translations;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    public function setAlias(string $alias): static
    {
        $this->alias = $alias;

        return $this;
    }

    public function getRestaurant(): ?Restaurant
    {
        return $this->restaurant;
    }

    public function setRestaurant(?Restaurant $restaurant): static
    {
        $this->restaurant = $restaurant;

        return $this;
    }
}
