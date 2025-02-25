<?php

namespace App\Entity;

use App\Repository\KindMenuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Trait\TranslatableDirectionTrait;
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: KindMenuRepository::class)]
class KindMenu implements TranslatableInterface
{
    use TranslatableDirectionTrait;

    #[Groups(['view'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['view'])]
    #[ORM\ManyToOne(inversedBy: 'kindMenus')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Restaurant $restaurant = null;

    #[Groups(['view'])]
    #[ORM\Column(length: 255)]
    private ?string $alias = null;

    #[Groups(['view'])]
    #[ORM\Column]
    private ?bool $isActive = null;

    #[Groups(['view'])]
    protected $translations;

    /**
     * @var Collection<int, MenuItem>
     */
    #[ORM\OneToMany(mappedBy: 'kindMenu', targetEntity: MenuItem::class)]
    private Collection $menuItems;

    public function __construct()
    {
        $this->menuItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    public function setAlias(string $alias): static
    {
        $this->alias = $alias;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * @return Collection<int, MenuItem>
     */
    public function getMenuItems(): Collection
    {
        return $this->menuItems;
    }

    public function addMenuItem(MenuItem $menuItem): static
    {
        if (!$this->menuItems->contains($menuItem)) {
            $this->menuItems->add($menuItem);
            $menuItem->setKindMenu($this);
        }

        return $this;
    }

    public function removeMenuItem(MenuItem $menuItem): static
    {
        if ($this->menuItems->removeElement($menuItem)) {
            // set the owning side to null (unless already changed)
            if ($menuItem->getKindMenu() === $this) {
                $menuItem->setKindMenu(null);
            }
        }

        return $this;
    }
}
