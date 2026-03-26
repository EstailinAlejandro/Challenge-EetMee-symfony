<?php

namespace App\Entity;

use App\Repository\ReviewRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReviewRepository::class)]
class Review
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $comment = null;

    #[ORM\Column]
    private ?int $rating = null;

    #[ORM\Column]
    private ?\DateTime $dateTime = null;

    #[ORM\ManyToOne(inversedBy: 'review')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $Reviewer = null;

    #[ORM\ManyToOne(inversedBy: 'reviewee')]
    private ?User $Reviewee = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): static
    {
        $this->comment = $comment;

        return $this;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): static
    {
        $this->rating = $rating;

        return $this;
    }

    public function getDateTime(): ?\DateTime
    {
        return $this->dateTime;
    }

    public function setDateTime(\DateTime $dateTime): static
    {
        $this->dateTime = $dateTime;

        return $this;
    }

    public function getReviewer(): ?User
    {
        return $this->Reviewer;
    }

    public function setReviewer(?User $Reviewer): static
    {
        $this->Reviewer = $Reviewer;

        return $this;
    }

    public function getReviewee(): ?User
    {
        return $this->Reviewee;
    }

    public function setReviewee(?User $Reviewee): static
    {
        $this->Reviewee = $Reviewee;

        return $this;
    }
}
