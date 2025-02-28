<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['view'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'ordes')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['view'])]
    private ?Table $orderedTable = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['view'])]
    private ?User $user = null;

    /**
     * @var Collection<int, MenuItem>
     */
    #[ORM\ManyToMany(targetEntity: MenuItem::class, inversedBy: 'orders')]
    #[Groups(['view'])]
    private Collection $menuItem;

    #[ORM\Column]
    #[Groups(['view'])]
    private ?\DateTimeImmutable $timeFrom = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['view'])]
    private ?float $amount = null;

    #[ORM\Column(length: 510, nullable: true)]
    #[Groups(['view'])]
    private ?string $comment = null;

    public function __construct()
    {
        $this->menuItem = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderedTable(): ?Table
    {
        return $this->orderedTable;
    }

    public function setOrderedTable(?Table $orderedTable): static
    {
        $this->orderedTable = $orderedTable;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, MenuItem>
     */
    public function getMenuItem(): Collection
    {
        return $this->menuItem;
    }

    public function addMenuItem(MenuItem $menuItem): static
    {
        if (!$this->menuItem->contains($menuItem)) {
            $this->menuItem->add($menuItem);
        }

        return $this;
    }

    public function removeMenuItem(MenuItem $menuItem): static
    {
        $this->menuItem->removeElement($menuItem);

        return $this;
    }

    public function getTimeFrom(): ?\DateTimeImmutable
    {
        return $this->timeFrom;
    }

    public function setTimeFrom(\DateTimeImmutable $timeFrom): static
    {
        $this->timeFrom = $timeFrom;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(?float $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): static
    {
        $this->comment = $comment;

        return $this;
    }
}
