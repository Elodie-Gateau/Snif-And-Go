<?php

namespace App\Entity;

use App\Repository\TrailRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrailRepository::class)]
class Trail
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?float $distance = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $starting_point = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $ending_point = null;

    #[ORM\Column]
    private ?float $duration = null;

    #[ORM\Column(length: 255)]
    private ?string $difficulty = null;

    #[ORM\Column]
    private ?float $score = null;

    #[ORM\Column(length: 255)]
    private ?string $water_point = null;

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

    public function getDistance(): ?float
    {
        return $this->distance;
    }

    public function setDistance(float $distance): static
    {
        $this->distance = $distance;

        return $this;
    }

    public function getStartingPoint(): ?string
    {
        return $this->starting_point;
    }

    public function setStartingPoint(string $starting_point): static
    {
        $this->starting_point = $starting_point;

        return $this;
    }

    public function getEndingPoint(): ?string
    {
        return $this->ending_point;
    }

    public function setEndingPoint(string $ending_point): static
    {
        $this->ending_point = $ending_point;

        return $this;
    }

    public function getDuration(): ?float
    {
        return $this->duration;
    }

    public function setDuration(float $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getDifficulty(): ?string
    {
        return $this->difficulty;
    }

    public function setDifficulty(string $difficulty): static
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    public function getScore(): ?float
    {
        return $this->score;
    }

    public function setScore(float $score): static
    {
        $this->score = $score;

        return $this;
    }

    public function getWaterPoint(): ?string
    {
        return $this->water_point;
    }

    public function setWaterPoint(string $water_point): static
    {
        $this->water_point = $water_point;

        return $this;
    }
}
