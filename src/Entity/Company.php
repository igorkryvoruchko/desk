<?php

namespace App\Entity;

use App\Entity\Contract\SoftDeletableInterface;
use App\Repository\CompanyRepository;
use App\Trait\SoftDeletableTrait;
use App\Trait\TranslatableDirectionTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Gedmo\Mapping\Annotation as Gedmo;

#[Gedmo\SoftDeleteable(fieldName: "deletedAt", timeAware: false)]
#[ORM\Entity(repositoryClass: CompanyRepository::class)]
class Company implements TranslatableInterface, SoftDeletableInterface
{
    use TranslatableDirectionTrait, SoftDeletableTrait;
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['view'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Groups(['view'])]
    private ?string $alias = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['view'])]
    private ?string $logo = null;

    #[Groups(['view'])]
    protected $translations;

    /**
     * @var Collection<int, Restaurant>
     */
    #[ORM\OneToMany(mappedBy: 'company', targetEntity: Restaurant::class, cascade: ['persist', 'remove'])]
    private Collection $restaurants;

    /**
     * @var Collection<int, User>
     */
    #[ORM\OneToMany(mappedBy: 'company', targetEntity: User::class)]
    private Collection $user;

    public function __construct()
    {
        $this->restaurants = new ArrayCollection();
        $this->user = new ArrayCollection();
    }

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

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): static
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * @return Collection<int, Restaurant>
     */
    public function getRestaurants(): Collection
    {
        return $this->restaurants;
    }

    public function addRestaurant(Restaurant $restaurant): static
    {
        if (!$this->restaurants->contains($restaurant)) {
            $this->restaurants->add($restaurant);
            $restaurant->setCompany($this);
        }

        return $this;
    }

    public function removeRestaurant(Restaurant $restaurant): static
    {
        if ($this->restaurants->removeElement($restaurant)) {
            // set the owning side to null (unless already changed)
            if ($restaurant->getCompany() === $this) {
                $restaurant->setCompany(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(User $user): static
    {
        if (!$this->user->contains($user)) {
            $this->user->add($user);
            $user->setCompany($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->user->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getCompany() === $this) {
                $user->setCompany(null);
            }
        }

        return $this;
    }
}
