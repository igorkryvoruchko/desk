<?php

namespace App\Entity;

use App\Entity\Contract\SoftDeletableInterface;
use App\Repository\RestaurantRepository;
use App\Trait\SoftDeletableTrait;
use App\Trait\TranslatableDirectionTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Gedmo\Mapping\Annotation as Gedmo;

#[Gedmo\SoftDeleteable(fieldName: "deletedAt", timeAware: false)]
#[ORM\Entity(repositoryClass: RestaurantRepository::class)]
class Restaurant implements TranslatableInterface, SoftDeletableInterface
{
    use TranslatableDirectionTrait, SoftDeletableTrait;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['view'])]
    private ?int $id = null;

    #[Groups(['view'])]
    #[ORM\ManyToOne(inversedBy: 'restaurants')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Company $company = null;

    #[Groups(['view'])]
    protected $translations;

    #[Groups(['view'])]
    #[ORM\Column(length: 255)]
    private ?string $alias = null;

    #[Groups(['view'])]
    #[ORM\Column(length: 255)]
    private ?string $address = null;

    #[Groups(['view'])]
    #[ORM\Column(length: 255)]
    private ?string $type = null;

    /**
     * @var Collection<int, Zone>
     */
    #[ORM\OneToMany(mappedBy: 'restaurant', targetEntity: Zone::class, cascade: ['persist', 'remove'])]
    private Collection $zones;

    /**
     * @var Collection<int, TypeMenu>
     */
    #[ORM\OneToMany(mappedBy: 'restaurant', targetEntity: KindMenu::class, cascade: ['persist', 'remove'])]
    private Collection $kindMenus;

    #[ORM\ManyToOne(inversedBy: 'restaurants')]
    #[ORM\JoinColumn(nullable: false)]
    private ?City $city = null;

    #[ORM\Column]
    private ?int $postalCode = null;

    public function __construct()
    {
        $this->zones = new ArrayCollection();
        $this->kindMenus = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): static
    {
        $this->company = $company;

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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, Zone>
     */
    public function getZones(): Collection
    {
        return $this->zones;
    }

    public function addZone(Zone $zone): static
    {
        if (!$this->zones->contains($zone)) {
            $this->zones->add($zone);
            $zone->setRestaurant($this);
        }

        return $this;
    }

    public function removeZone(Zone $zone): static
    {
        if ($this->zones->removeElement($zone)) {
            // set the owning side to null (unless already changed)
            if ($zone->getRestaurant() === $this) {
                $zone->setRestaurant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TypeMenu>
     */
    public function getKindMenus(): Collection
    {
        return $this->kindMenus;
    }

    public function addKindMenu(KindMenu $kindMenu): static
    {
        if (!$this->kindMenus->contains($kindMenu)) {
            $this->kindMenus->add($kindMenu);
            $kindMenu->setRestaurant($this);
        }

        return $this;
    }

    public function removeKindMenu(KindMenu $kindMenu): static
    {
        if ($this->kindMenus->removeElement($kindMenu)) {
            // set the owning side to null (unless already changed)
            if ($kindMenu->getRestaurant() === $this) {
                $kindMenu->setRestaurant(null);
            }
        }

        return $this;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getPostalCode(): ?int
    {
        return $this->postalCode;
    }

    public function setPostalCode(int $postalCode): static
    {
        $this->postalCode = $postalCode;

        return $this;
    }
}
