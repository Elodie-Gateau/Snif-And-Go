<?php

namespace App\Entity;

use App\Repository\DogRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DogRepository::class)]
class Dog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?\DateTime $birth_date = null;

    #[ORM\Column(length: 255)]
    private ?string $sex = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $identity_number = null;

    #[ORM\ManyToOne(inversedBy: 'dogs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    /**
     * @var Collection<int, WalkRegistration>
     */
    #[ORM\OneToMany(targetEntity: WalkRegistration::class, mappedBy: 'dog')]
    private Collection $walk_registration;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photo = null;

    #[ORM\ManyToOne(inversedBy: 'dogs')]
    private ?DogBreed $dogBreed = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    public function __construct()
    {
        $this->walk_registration = new ArrayCollection();
        $this->status = "Active";
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getBirthDate(): ?\DateTime
    {
        return $this->birth_date;
    }

    public function setBirthDate(\DateTime $birth_date): static
    {
        $this->birth_date = $birth_date;

        return $this;
    }

    public function getAge(): ?int
    {
        $birthDate = $this->getBirthDate();
        if ($birthDate) {
            $now = new DateTime('now');
            $bd = $birthDate->getTimestamp();
            $nowb = $now->getTimestamp();
            $age = ($nowb - $bd) / (365 * 24 * 60 * 60);
            return $age;
        } else {
            return null;
        }
    }

    public function getAgeM(): ?int
    {
        $birthDate = $this->getBirthDate();
        if ($birthDate) {
            $now = new DateTime('now');
            $bd = $birthDate->getTimestamp();
            $nowb = $now->getTimestamp();
            $age = ($nowb - $bd) / ((365 / 12) * 24 * 60 * 60);
            return $age;
        } else {
            return null;
        }
    }

    public function getSex(): ?string
    {
        return $this->sex;
    }

    public function setSex(string $sex): static
    {
        $this->sex = $sex;

        return $this;
    }

    public function getIdentityNumber(): ?string
    {
        return $this->identity_number;
    }

    public function setIdentityNumber(?string $identity_number): static
    {
        $this->identity_number = $identity_number;

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
     * @return Collection<int, WalkRegistration>
     */
    public function getWalkRegistration(): Collection
    {
        return $this->walk_registration;
    }

    public function addWalkRegistration(WalkRegistration $walkRegistration): static
    {
        if (!$this->walk_registration->contains($walkRegistration)) {
            $this->walk_registration->add($walkRegistration);
            $walkRegistration->setDog($this);
        }

        return $this;
    }

    public function removeWalkRegistration(WalkRegistration $walkRegistration): static
    {
        if ($this->walk_registration->removeElement($walkRegistration)) {
            // set the owning side to null (unless already changed)
            if ($walkRegistration->getDog() === $this) {
                $walkRegistration->setDog(null);
            }
        }

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

    public function getDogBreed(): ?DogBreed
    {
        return $this->dogBreed;
    }

    public function setDogBreed(?DogBreed $dogBreed): static
    {
        $this->dogBreed = $dogBreed;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }
}
