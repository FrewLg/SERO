<?php

namespace App\Entity\SERO;

use App\Entity\User;
use App\Repository\SERO\ReviewAssignmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReviewAssignmentRepository::class)]
class ReviewAssignment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'reviewAssignments')]
    private ?Application $application = null;

    #[ORM\ManyToOne(inversedBy: 'reviewAssignments')]
    private ?User $irbreviewer = null;

    #[ORM\Column(nullable: true)]
    private ?bool $closed = null;

    #[ORM\Column(nullable: true)]
    private ?bool $allowToView = null;

    /**
     * @var Collection<int, ReviewerResponse>
     */
    #[ORM\OneToMany(targetEntity: ReviewerResponse::class, mappedBy: 'reviewAssignment')]
    private Collection $reviewerResponses;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $InvitationSentAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dueDate = null;

    #[ORM\Column(nullable: true)]
    private ?int $status = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $recommendation = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $reviewedAt = null;

    #[ORM\ManyToOne(inversedBy: 'reviewAssignments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ReviewForm $reviewForm = null;

    #[ORM\Column(length: 255)]
    private ?string $reviewerType = null;

    public function __construct()
    {
        $this->reviewerResponses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getApplication(): ?Application
    {
        return $this->application;
    }

    public function setApplication(?Application $application): static
    {
        $this->application = $application;

        return $this;
    }

    public function getIrbreviewer(): ?User
    {
        return $this->irbreviewer;
    }

    public function setIrbreviewer(?User $irbreviewer): static
    {
        $this->irbreviewer = $irbreviewer;

        return $this;
    }

    public function isClosed(): ?bool
    {
        return $this->closed;
    }

    public function setClosed(?bool $closed): static
    {
        $this->closed = $closed;

        return $this;
    }

    public function isAllowToView(): ?bool
    {
        return $this->allowToView;
    }

    public function setAllowToView(?bool $allowToView): static
    {
        $this->allowToView = $allowToView;

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
            $reviewerResponse->setReviewAssignment($this);
        }

        return $this;
    }

    public function removeReviewerResponse(ReviewerResponse $reviewerResponse): static
    {
        if ($this->reviewerResponses->removeElement($reviewerResponse)) {
            // set the owning side to null (unless already changed)
            if ($reviewerResponse->getReviewAssignment() === $this) {
                $reviewerResponse->setReviewAssignment(null);
            }
        }

        return $this;
    }

    public function getInvitationSentAt(): ?\DateTimeInterface
    {
        return $this->InvitationSentAt;
    }

    public function setInvitationSentAt(?\DateTimeInterface $InvitationSentAt): static
    {
        $this->InvitationSentAt = $InvitationSentAt;

        return $this;
    }

    public function getDueDate(): ?\DateTimeInterface
    {
        return $this->dueDate;
    }

    public function setDueDate(?\DateTimeInterface $dueDate): static
    {
        $this->dueDate = $dueDate;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(?int $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getRecommendation(): ?string
    {
        return $this->recommendation;
    }

    public function setRecommendation(?string $recommendation): static
    {
        $this->recommendation = $recommendation;

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

    public function getReviewForm(): ?ReviewForm
    {
        return $this->reviewForm;
    }

    public function setReviewForm(?ReviewForm $reviewForm): static
    {
        $this->reviewForm = $reviewForm;

        return $this;
    }

    public function getReviewerType(): ?string
    {
        return $this->reviewerType;
    }

    public function setReviewerType(string $reviewerType): static
    {
        $this->reviewerType = $reviewerType;

        return $this;
    }
}
