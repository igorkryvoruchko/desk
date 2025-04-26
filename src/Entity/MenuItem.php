<?php

namespace App\Entity;

use App\Entity\Contract\SoftDeletableInterface;
use App\Repository\MenuItemRepository;
use App\Trait\SoftDeletableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Trait\TranslatableDirectionTrait;
use Symfony\Component\Serializer\Annotation\Groups;
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;

#[Gedmo\SoftDeleteable(fieldName: "deletedAt", timeAware: false)]
#[ORM\Entity(repositoryClass: MenuItemRepository::class)]
#[UniqueEntity(
    fields: ['alias', 'kindMenu'],
    message: 'This alias is already in use on that KindMenu.',
    errorPath: 'alias',
)]
class MenuItem implements TranslatableInterface, SoftDeletableInterface
{
    use TranslatableDirectionTrait, SoftDeletableTrait;

    #[Groups(['view'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['view'])]
    #[ORM\ManyToOne(inversedBy: 'menuItems')]
    private ?KindMenu $kindMenu = null;

    #[Groups(['view'])]
    #[ORM\Column(length: 255)]
    private ?string $alias = null;

    #[Groups(['view'])]
    #[ORM\Column]
    private ?int $quantity = null;

    #[Groups(['view'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photo = null;

    #[Groups(['view'])]
    protected $translations;

    #[Groups(['view'])]
    #[ORM\Column]
    private ?float $price = null;

    #[Groups(['view'])]
    #[ORM\Column(nullable: true)]
    private ?float $specialPrice = null;

    /**
     * @var Collection<int, Order>
     */
    #[ORM\ManyToMany(targetEntity: Order::class, mappedBy: 'menuItem', cascade: ['persist', 'remove'])]
    private Collection $orders;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getKindMenu(): ?KindMenu
    {
        return $this->kindMenu;
    }

    public function setKindMenu(?KindMenu $kindMenu): static
    {
        $this->kindMenu = $kindMenu;

        return $this;
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

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): static
    {
        $this->photo = $photo;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getSpecialPrice(): ?float
    {
        return $this->specialPrice;
    }

    public function setSpecialPrice(?float $specialPrice): static
    {
        $this->specialPrice = $specialPrice;

        return $this;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): static
    {
        if (!$this->orders->contains($order)) {
            $this->orders->add($order);
            $order->addMenuItem($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): static
    {
        if ($this->orders->removeElement($order)) {
            $order->removeMenuItem($this);
        }

        return $this;
    }
}
