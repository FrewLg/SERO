<?php

namespace App\Entity\SERO;

use App\Repository\SERO\ReviewerResponseRepository;
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
}
