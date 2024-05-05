<?php

namespace App\Entity\SERO;

use App\Entity\User;
use App\Repository\SERO\ReviewerResponseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReviewerResponseRepository::class)]
class ReviewerResponse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'reviewerResponses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ReviewChecklist $checklist = null; 

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $answer = null;

    #[ORM\ManyToOne(inversedBy: 'reviewerResponses')]
    private ?ReviewAssignment $reviewAssignment = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $comment = null;

    #[ORM\ManyToOne(inversedBy: 'reviewerResponses')]
    private ?User $reviewedBy = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $reviewedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getChecklist(): ?ReviewChecklist
    {
        return $this->checklist;
    }

    public function setChecklist(?ReviewChecklist $checklist): self
    {
        $this->checklist = $checklist;

        return $this;
    }

    public function __toString()
    {
       return $this->checklist;
    }
    
    public function getAnswer(): ?string
    {
        return $this->answer;
    }

    public function setAnswer(?string $answer): static
    {
        $this->answer = $answer;

        return $this;
    }

    public function getReviewAssignment(): ?ReviewAssignment
    {
        return $this->reviewAssignment;
    }

    public function setReviewAssignment(?ReviewAssignment $reviewAssignment): static
    {
        $this->reviewAssignment = $reviewAssignment;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): static
    {
        $this->comment = $comment;

        return $this;
    }

    public function getReviewedBy(): ?User
    {
        return $this->reviewedBy;
    }

    public function setReviewedBy(?User $reviewedBy): static
    {
        $this->reviewedBy = $reviewedBy;

        return $this;
    }

    public function getReviewedAt(): ?\DateTimeInterface
    {
        return $this->reviewedAt;
    }

    public function setReviewedAt(?\DateTimeInterface $reviewedAt): static
    {
        $this->reviewedAt = $reviewedAt;

        return $this;
    }
}
