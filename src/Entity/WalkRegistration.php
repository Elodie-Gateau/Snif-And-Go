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
}
