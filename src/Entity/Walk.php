<?php

namespace App\Entity;

use App\Repository\WalkRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WalkRepository::class)]
class Walk
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTime $date = null;

    #[ORM\Column]
    private ?int $max_dogs = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    /**
     * @var Collection<int, WalkRegistration>
     */
    #[ORM\OneToMany(targetEntity: WalkRegistration::class, mappedBy: 'walk')]
    private Collection $walk_registration;

    #[ORM\ManyToOne(inversedBy: 'walks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Trail $trail = null;

    /**
     * @var Collection<int, Photo>
     */
    #[ORM\OneToMany(targetEntity: Photo::class, mappedBy: 'walk')]
    private Collection $photos;

    public function __construct()
    {
        $this->walk_registration = new ArrayCollection();
        $this->photos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getMaxDogs(): ?int
    {
        return $this->max_dogs;
    }

    public function setMaxDogs(int $max_dogs): static
    {
        $this->max_dogs = $max_dogs;

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
            $walkRegistration->setWalk($this);
        }

        return $this;
    }

    public function removeWalkRegistration(WalkRegistration $walkRegistration): static
    {
        if ($this->walk_registration->removeElement($walkRegistration)) {
            // set the owning side to null (unless already changed)
            if ($walkRegistration->getWalk() === $this) {
                $walkRegistration->setWalk(null);
            }
        }

        return $this;
    }

    public function getTrail(): ?Trail
    {
        return $this->trail;
    }

    public function setTrail(?Trail $trail): static
    {
        $this->trail = $trail;

        return $this;
    }

    /**
     * @return Collection<int, Photo>
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function addPhoto(Photo $photo): static
    {
        if (!$this->photos->contains($photo)) {
            $this->photos->add($photo);
            $photo->setWalk($this);
        }

        return $this;
    }

    public function removePhoto(Photo $photo): static
    {
        if ($this->photos->removeElement($photo)) {
            // set the owning side to null (unless already changed)
            if ($photo->getWalk() === $this) {
                $photo->setWalk(null);
            }
        }

        return $this;
    }
}
