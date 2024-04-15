<?php

namespace App\Entity\SERO;

use App\Repository\ReviewChecklistRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReviewChecklistRepository::class)]
class ReviewChecklist
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    private ?int $answerType = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'reviewChecklists')]
    private ?self $parent = null; 

    /**
     * @var Collection<int, self>
     */
    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'parent')]
    private Collection $reviewChecklists;

    /**
     * @var Collection<int, ReviewerResponse>
     */
    #[ORM\OneToMany(targetEntity: ReviewerResponse::class, mappedBy: 'checklist', orphanRemoval: true)]
    private Collection $reviewerResponses;

   

    #[ORM\ManyToOne(inversedBy: 'reviewChecklists')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ReviewChecklistGroup $checklistGroup = null;

    public function __construct()
    {
        $this->reviewChecklists = new ArrayCollection();
        $this->reviewerResponses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function __toString()
    {
            return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getAnswerType(): ?int
    {
        return $this->answerType;
    }

    public function setAnswerType(?int $answerType): static
    {
        $this->answerType = $answerType;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): static
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getReviewChecklists(): Collection
    {
        return $this->reviewChecklists;
    }

    public function addReviewChecklist(self $reviewChecklist): static
    {
        if (!$this->reviewChecklists->contains($reviewChecklist)) {
            $this->reviewChecklists->add($reviewChecklist);
            $reviewChecklist->setParent($this);
        }

        return $this;
    }

    public function removeReviewChecklist(self $reviewChecklist): static
    {
        if ($this->reviewChecklists->removeElement($reviewChecklist)) {
            // set the owning side to null (unless already changed)
            if ($reviewChecklist->getParent() === $this) {
                $reviewChecklist->setParent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ReviewerResponse>
     */
    public function getReviewerResponses(): Collection
    {
        return $this->reviewerResponses;
    }

    public function addReviewerResponse(ReviewerResponse $reviewerResponse): static
    {
        if (!$this->reviewerResponses->contains($reviewerResponse)) {
            $this->reviewerResponses->add($reviewerResponse);
            $reviewerResponse->setChecklist($this);
        }

        return $this;
    }

    public function removeReviewerResponse(ReviewerResponse $reviewerResponse): static
    {
        if ($this->reviewerResponses->removeElement($reviewerResponse)) {
            // set the owning side to null (unless already changed)
            if ($reviewerResponse->getChecklist() === $this) {
                $reviewerResponse->setChecklist(null);
            }
        }

        return $this;
    }

   

    public function getChecklistGroup(): ?ReviewChecklistGroup
    {
        return $this->checklistGroup;
    }

    public function setChecklistGroup(?ReviewChecklistGroup $checklistGroup): static
    {
        $this->checklistGroup = $checklistGroup;

        return $this;
    }
}
