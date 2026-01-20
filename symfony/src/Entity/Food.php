<?php

namespace App\Entity;

use App\Repository\FoodRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FoodRepository::class)]
class Food
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column]
    private ?int $portions = null;

    #[ORM\ManyToOne(inversedBy: 'deliver')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $userDeliver = null;

    #[ORM\ManyToOne(inversedBy: 'pickup')]
    private ?User $pickupUser = null;

    #[ORM\ManyToOne(inversedBy: 'food')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Box $box = null;

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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getPortions(): ?int
    {
        return $this->portions;
    }

    public function setPortions(int $portions): static
    {
        $this->portions = $portions;

        return $this;
    }

    public function getUserDeliver(): ?User
    {
        return $this->userDeliver;
    }

    public function setUserDeliver(?User $userDeliver): static
    {
        $this->userDeliver = $userDeliver;

        return $this;
    }

    public function getPickupUser(): ?User
    {
        return $this->pickupUser;
    }

    public function setPickupUser(?User $pickupUser): static
    {
        $this->pickupUser = $pickupUser;

        return $this;
    }

    public function getBox(): ?Box
    {
        return $this->box;
    }

    public function setBox(?Box $box): static
    {
        $this->box = $box;

        return $this;
    }
}
