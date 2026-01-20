<?php

namespace App\Entity;

use App\Repository\BoxRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BoxRepository::class)]
class Box
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $city = null;

    #[ORM\Column(length: 255)]
    private ?string $street = null;

    #[ORM\Column]
    private ?int $number = null;

    #[ORM\Column(length: 8)]
    private ?string $zipcode = null;

    #[ORM\Column]
    private ?bool $isFull = null;

    /**
     * @var Collection<int, Food>
     */
    #[ORM\OneToMany(targetEntity: Food::class, mappedBy: 'box')]
    private Collection $food;

    /**
     * @var Collection<int, Temperature>
     */
    #[ORM\OneToMany(targetEntity: Temperature::class, mappedBy: 'box')]
    private Collection $temperature;

    #[ORM\Column]
    private ?bool $pickup_requested = null;

    #[ORM\Column]
    private ?bool $is_open = null;

    public function __construct()
    {
        $this->food = new ArrayCollection();
        $this->temperature = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): static
    {
        $this->street = $street;

        return $this;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): static
    {
        $this->number = $number;

        return $this;
    }

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function setZipcode(string $zipcode): static
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function isFull(): ?bool
    {
        return $this->isFull;
    }

    public function setIsFull(bool $isFull): static
    {
        $this->isFull = $isFull;

        return $this;
    }

    /**
     * @return Collection<int, Food>
     */
    public function getFood(): Collection
    {
        return $this->food;
    }

    public function addFood(Food $food): static
    {
        if (!$this->food->contains($food)) {
            $this->food->add($food);
            $food->setBox($this);
        }

        return $this;
    }

    public function removeFood(Food $food): static
    {
        if ($this->food->removeElement($food)) {
            // set the owning side to null (unless already changed)
            if ($food->getBox() === $this) {
                $food->setBox(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Temperature>
     */
    public function getTemperature(): Collection
    {
        return $this->temperature;
    }

    public function addTemperature(Temperature $temperature): static
    {
        if (!$this->temperature->contains($temperature)) {
            $this->temperature->add($temperature);
            $temperature->setBox($this);
        }

        return $this;
    }

    public function removeTemperature(Temperature $temperature): static
    {
        if ($this->temperature->removeElement($temperature)) {
            // set the owning side to null (unless already changed)
            if ($temperature->getBox() === $this) {
                $temperature->setBox(null);
            }
        }

        return $this;
    }

    public function isPickupRequested(): ?bool
    {
        return $this->pickup_requested;
    }

    public function setPickupRequested(bool $pickup_requested): static
    {
        $this->pickup_requested = $pickup_requested;

        return $this;
    }

    public function isOpen(): ?bool
    {
        return $this->is_open;
    }

    public function setIsOpen(bool $is_open): static
    {
        $this->is_open = $is_open;

        return $this;
    }

}
