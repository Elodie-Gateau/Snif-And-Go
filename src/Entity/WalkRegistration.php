<?php

namespace App\Entity;

use App\Repository\WalkRegistrationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WalkRegistrationRepository::class)]
class WalkRegistration
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTime $date_registration = null;

    #[ORM\ManyToOne(inversedBy: 'walk_registration')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Dog $dog = null;

    #[ORM\ManyToOne(inversedBy: 'walk_registration')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Walk $walk = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateRegistration(): ?\DateTime
    {
        return $this->date_registration;
    }

    public function setDateRegistration(\DateTime $date_registration): static
    {
        $this->date_registration = $date_registration;

        return $this;
    }

    public function getDog(): ?Dog
    {
        return $this->dog;
    }

    public function setDog(?Dog $dog): static
    {
        $this->dog = $dog;

        return $this;
    }

    public function getWalk(): ?Walk
    {
        return $this->walk;
    }

    public function setWalk(?Walk $walk): static
    {
        $this->walk = $walk;

        return $this;
    }
}
