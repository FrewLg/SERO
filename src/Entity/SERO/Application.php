<?php

namespace App\Entity\SERO;

use App\Entity\SERO\ApplicationReview;
use App\Entity\User;
use App\Repository\ApplicationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ApplicationRepository::class)]
class Application
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $title = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $location = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    /**
     * @var Collection<int, ApplicationReview>
     */
    #[ORM\OneToMany(targetEntity: ApplicationReview::class, mappedBy: 'application', orphanRemoval: true)]
    private Collection $applicationReviews;

    /**
     * @var Collection<int, ReviewAssignment>
     */
    #[ORM\OneToMany(targetEntity: ReviewAssignment::class, mappedBy: 'application')]
    private Collection $reviewAssignments;

    /**
     * @var Collection<int, IrbCertificate>
     */
    #[ORM\OneToMany(targetEntity: IrbCertificate::class, mappedBy: 'irbApplication')]
    private Collection $irbCertificates;

    #[ORM\ManyToOne(inversedBy: 'applications')]
    private ?Meeting $meeting = null;

    /**
     * @var Collection<int, ApplicationFeedback>
     */
    #[ORM\OneToMany(targetEntity: ApplicationFeedback::class, mappedBy: 'application')]
    private Collection $applicationFeedback;

 
    #[ORM\ManyToOne(inversedBy: 'applications')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $submittedBy = null;

    /**
     * @var Collection<int, Version>
     */
    #[ORM\OneToMany(targetEntity: Version::class, mappedBy: 'application', orphanRemoval: true)]
    private Collection $versions;

    /**
     * @var Collection<int, Continuation>
     */
    #[ORM\OneToMany(targetEntity: Continuation::class, mappedBy: 'application', orphanRemoval: true)]
    private Collection $continuations;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ibcode = null;
 
    public function __construct()
    {
        $this->applicationReviews = new ArrayCollection();
        $this->reviewAssignments = new ArrayCollection();
        $this->irbCertificates = new ArrayCollection();
        $this->applicationFeedback = new ArrayCollection();
        $this->versions = new ArrayCollection();
        $this->continuations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTimeInterface $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeInterface $endDate): static
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, ApplicationReview>
     */
    public function getApplicationReviews(): Collection
    {
        return $this->applicationReviews;
    }

    public function addApplicationReview(ApplicationReview $applicationReview): static
    {
        if (!$this->applicationReviews->contains($applicationReview)) {
            $this->applicationReviews->add($applicationReview);
            $applicationReview->setApplication($this);
        }

        return $this;
    }

    public function removeApplicationReview(ApplicationReview $applicationReview): static
    {
        if ($this->applicationReviews->removeElement($applicationReview)) {
            // set the owning side to null (unless already changed)
            if ($applicationReview->getApplication() === $this) {
                $applicationReview->setApplication(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ReviewAssignment>
     */
    public function getReviewAssignments(): Collection
    {
        return $this->reviewAssignments;
    }

    public function addReviewAssignment(ReviewAssignment $reviewAssignment): static
    {
        if (!$this->reviewAssignments->contains($reviewAssignment)) {
            $this->reviewAssignments->add($reviewAssignment);
            $reviewAssignment->setApplication($this);
        }

        return $this;
    }

    public function removeReviewAssignment(ReviewAssignment $reviewAssignment): static
    {
        if ($this->reviewAssignments->removeElement($reviewAssignment)) {
            // set the owning side to null (unless already changed)
            if ($reviewAssignment->getApplication() === $this) {
                $reviewAssignment->setApplication(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, IrbCertificate>
     */
    public function getIrbCertificates(): Collection
    {
        return $this->irbCertificates;
    }

    public function addIrbCertificate(IrbCertificate $irbCertificate): static
    {
        if (!$this->irbCertificates->contains($irbCertificate)) {
            $this->irbCertificates->add($irbCertificate);
            $irbCertificate->setIrbApplication($this);
        }

        return $this;
    }

    public function removeIrbCertificate(IrbCertificate $irbCertificate): static
    {
        if ($this->irbCertificates->removeElement($irbCertificate)) {
            // set the owning side to null (unless already changed)
            if ($irbCertificate->getIrbApplication() === $this) {
                $irbCertificate->setIrbApplication(null);
            }
        }

        return $this;
    }

    public function getMeeting(): ?Meeting
    {
        return $this->meeting;
    }

    public function setMeeting(?Meeting $meeting): static
    {
        $this->meeting = $meeting;

        return $this;
    }

    /**
     * @return Collection<int, ApplicationFeedback>
     */
    public function getApplicationFeedback(): Collection
    {
        return $this->applicationFeedback;
    }

    public function addApplicationFeedback(ApplicationFeedback $applicationFeedback): static
    {
        if (!$this->applicationFeedback->contains($applicationFeedback)) {
            $this->applicationFeedback->add($applicationFeedback);
            $applicationFeedback->setApplication($this);
        }

        return $this;
    }

    public function removeApplicationFeedback(ApplicationFeedback $applicationFeedback): static
    {
        if ($this->applicationFeedback->removeElement($applicationFeedback)) {
            // set the owning side to null (unless already changed)
            if ($applicationFeedback->getApplication() === $this) {
                $applicationFeedback->setApplication(null);
            }
        }

        return $this;
    }

    
    public function getSubmittedBy(): ?User
    {
        return $this->submittedBy;
    }

    public function setSubmittedBy(?User $submittedBy): static
    {
        $this->submittedBy = $submittedBy;

        return $this;
    }

    /**
     * @return Collection<int, Version>
     */
    public function getVersions(): Collection
    {
        return $this->versions;
    }

    public function addVersion(Version $version): static
    {
        if (!$this->versions->contains($version)) {
            $this->versions->add($version);
            $version->setApplication($this);
        }

        return $this;
    }

    public function removeVersion(Version $version): static
    {
        if ($this->versions->removeElement($version)) {
            // set the owning side to null (unless already changed)
            if ($version->getApplication() === $this) {
                $version->setApplication(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Continuation>
     */
    public function getContinuations(): Collection
    {
        return $this->continuations;
    }

    public function addContinuation(Continuation $continuation): static
    {
        if (!$this->continuations->contains($continuation)) {
            $this->continuations->add($continuation);
            $continuation->setApplication($this);
        }

        return $this;
    }

    public function removeContinuation(Continuation $continuation): static
    {
        if ($this->continuations->removeElement($continuation)) {
            // set the owning side to null (unless already changed)
            if ($continuation->getApplication() === $this) {
                $continuation->setApplication(null);
            }
        }

        return $this;
    }

    public function getIbcode(): ?string
    {
        return $this->ibcode;
    }

    public function setIbcode(?string $ibcode): static
    {
        $this->ibcode = $ibcode;

        return $this;
    }

  
}
