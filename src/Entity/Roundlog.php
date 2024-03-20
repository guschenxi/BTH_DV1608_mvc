<?php

namespace App\Entity;

use App\Repository\RoundlogRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use \DataTime;

#[ORM\Entity(repositoryClass: RoundlogRepository::class)]
class Roundlog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $gamelog_id = null;

    #[ORM\Column]
    private ?int $winhands = null;

    #[ORM\Column]
    private ?float $difference = null;

    #[ORM\Column]
    private ?float $newbalance = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $timestamp = null;

    public function __construct()
    {
        $this->timestamp = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGamelogId(): ?int
    {
        return $this->gamelog_id;
    }

    public function setGamelogId(int $gamelog_id): static
    {
        $this->gamelog_id = $gamelog_id;

        return $this;
    }

    public function getWinhands(): ?int
    {
        return $this->winhands;
    }

    public function setWinhands(int $winhands): static
    {
        $this->winhands = $winhands;

        return $this;
    }

    public function getDifference(): ?float
    {
        return $this->difference;
    }

    public function setDifference(float $difference): static
    {
        $this->difference = $difference;

        return $this;
    }

    public function getNewbalance(): ?float
    {
        return $this->newbalance;
    }

    public function setNewbalance(float $newbalance): static
    {
        $this->newbalance = $newbalance;

        return $this;
    }

    public function getTimestamp(): ?\DateTimeInterface
    {
        return $this->timestamp;
    }

    public function setTimestamp(\DateTimeInterface $timestamp): static
    {
        $this->timestamp = $timestamp;

        return $this;
    }
}
