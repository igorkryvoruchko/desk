<?php

namespace App\Entity;

use App\Repository\RestaurantRepository;
use App\Trait\TranslatableDirectionTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: RestaurantRepository::class)]
class Restaurant implements TranslatableInterface
{
    use TranslatableDirectionTrait;
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
    #[ORM\OneToMany(mappedBy: 'restaurant', targetEntity: Zone::class)]
    private Collection $zones;

    public function __construct()
    {
        $this->zones = new ArrayCollection();
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
}
