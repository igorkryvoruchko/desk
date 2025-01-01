<?php

namespace App\Entity;

use App\Repository\TableRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TableRepository::class)]
#[ORM\Table(name: '`table`')]
class Table
{
    #[Groups(['view'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    #[Groups(['view'])]
    #[ORM\ManyToOne(inversedBy: 'tables')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Zone $zone = null;

    #[Groups(['view'])]
    #[ORM\Column]
    private ?int $number = null;

    #[Groups(['view'])]
    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $seatsCount = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getZone(): ?Zone
    {
        return $this->zone;
    }

    public function setZone(?Zone $zone): static
    {
        $this->zone = $zone;

        return $this;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): static
    {
        $this->number = $number;

        return $this;
    }

    public function getSeatsCount(): ?int
    {
        return $this->seatsCount;
    }

    public function setSeatsCount(int $seatsCount): static
    {
        $this->seatsCount = $seatsCount;

        return $this;
    }
}
