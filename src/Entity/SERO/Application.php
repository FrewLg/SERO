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

    /**
     * @var Collection<int, Icf>
     */
    #[ORM\OneToMany(targetEntity: Icf::class, mappedBy: 'application', orphanRemoval: true)]
    private Collection $icfs;

    #[ORM\ManyToOne(inversedBy: 'applications')]
    private ?StudyType $studyType = null;

    #[ORM\ManyToOne(inversedBy: 'applications')]
    #[ORM\JoinColumn(nullable: false)]
    private ?StudyPopulation $studyPopulation = null;

    #[ORM\ManyToOne(inversedBy: 'applications')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ParticipantCharacter $participantCharacter = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $requestedExclusionParticipant = null;

    /**
     * @var Collection<int, SpecialResRequirement>
     */
    #[ORM\ManyToMany(targetEntity: SpecialResRequirement::class, inversedBy: 'applications')]
    private Collection $specialResourceRequirement;

    #[ORM\ManyToOne(inversedBy: 'applications')]
    #[ORM\JoinColumn(nullable: false)]
    private ?IonizingRadiationUse $ionizingRadiationUse = null;

    #[ORM\ManyToOne(inversedBy: 'applications')]
    #[ORM\JoinColumn(nullable: false)]
    private ?InvestigationalNewDrug $investigationalNewDrug = null;

    #[ORM\ManyToOne(inversedBy: 'applications')]
    private ?ProceedureUse $proceedureUse = null;

    #[ORM\ManyToOne(inversedBy: 'applications')]
    #[ORM\JoinColumn(nullable: false)]
    private ?MultiSiteCollaboration $multiSiteCollaboration = null;

    #[ORM\ManyToOne(inversedBy: 'applications')]
    #[ORM\JoinColumn(nullable: false)]
    private ?FinancialDisclosure $financialDisclosure = null;

    /**
     * @var Collection<int, ParticipatingInvestigator>
     */
    #[ORM\OneToMany(targetEntity: ParticipatingInvestigator::class, mappedBy: 'application', orphanRemoval: true , cascade:["persist","remove"])]
    private Collection $participatingInvestigators;

    #[ORM\Column]
    private ?int $status = null;
 
    public function __construct()
    {
        $this->applicationReviews = new ArrayCollection();
        $this->reviewAssignments = new ArrayCollection();
        $this->irbCertificates = new ArrayCollection();
        $this->applicationFeedback = new ArrayCollection();
        $this->versions = new ArrayCollection();
        $this->continuations = new ArrayCollection();
        $this->icfs = new ArrayCollection();
        $this->specialResourceRequirement = new ArrayCollection();
        $this->participatingInvestigators = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function __toString()
    {
            return "[".$this->ibcode ."] ".$this->title;
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

    /**
     * @return Collection<int, Icf>
     */
    public function getIcfs(): Collection
    {
        return $this->icfs;
    }

    public function addIcf(Icf $icf): static
    {
        if (!$this->icfs->contains($icf)) {
            $this->icfs->add($icf);
            $icf->setApplication($this);
        }

        return $this;
    }

    public function removeIcf(Icf $icf): static
    {
        if ($this->icfs->removeElement($icf)) {
            // set the owning side to null (unless already changed)
            if ($icf->getApplication() === $this) {
                $icf->setApplication(null);
            }
        }

        return $this;
    }

    public function getStudyType(): ?StudyType
    {
        return $this->studyType;
    }

    public function setStudyType(?StudyType $studyType): static
    {
        $this->studyType = $studyType;

        return $this;
    }

    public function getStudyPopulation(): ?StudyPopulation
    {
        return $this->studyPopulation;
    }

    public function setStudyPopulation(?StudyPopulation $studyPopulation): static
    {
        $this->studyPopulation = $studyPopulation;

        return $this;
    }

    public function getParticipantCharacter(): ?ParticipantCharacter
    {
        return $this->participantCharacter;
    }

    public function setParticipantCharacter(?ParticipantCharacter $participantCharacter): static
    {
        $this->participantCharacter = $participantCharacter;

        return $this;
    }

    public function getRequestedExclusionParticipant(): ?string
    {
        return $this->requestedExclusionParticipant;
    }

    public function setRequestedExclusionParticipant(?string $requestedExclusionParticipant): static
    {
        $this->requestedExclusionParticipant = $requestedExclusionParticipant;

        return $this;
    }

    /**
     * @return Collection<int, SpecialResRequirement>
     */
    public function getSpecialResourceRequirement(): Collection
    {
        return $this->specialResourceRequirement;
    }

    public function addSpecialResourceRequirement(SpecialResRequirement $specialResourceRequirement): static
    {
        if (!$this->specialResourceRequirement->contains($specialResourceRequirement)) {
            $this->specialResourceRequirement->add($specialResourceRequirement);
        }

        return $this;
    }

    public function removeSpecialResourceRequirement(SpecialResRequirement $specialResourceRequirement): static
    {
        $this->specialResourceRequirement->removeElement($specialResourceRequirement);

        return $this;
    }

    public function getIonizingRadiationUse(): ?IonizingRadiationUse
    {
        return $this->ionizingRadiationUse;
    }

    public function setIonizingRadiationUse(?IonizingRadiationUse $ionizingRadiationUse): static
    {
        $this->ionizingRadiationUse = $ionizingRadiationUse;

        return $this;
    }

    public function getInvestigationalNewDrug(): ?InvestigationalNewDrug
    {
        return $this->investigationalNewDrug;
    }

    public function setInvestigationalNewDrug(?InvestigationalNewDrug $investigationalNewDrug): static
    {
        $this->investigationalNewDrug = $investigationalNewDrug;

        return $this;
    }

    public function getProceedureUse(): ?ProceedureUse
    {
        return $this->proceedureUse;
    }

    public function setProceedureUse(?ProceedureUse $proceedureUse): static
    {
        $this->proceedureUse = $proceedureUse;

        return $this;
    }

    public function getMultiSiteCollaboration(): ?MultiSiteCollaboration
    {
        return $this->multiSiteCollaboration;
    }

    public function setMultiSiteCollaboration(?MultiSiteCollaboration $multiSiteCollaboration): static
    {
        $this->multiSiteCollaboration = $multiSiteCollaboration;

        return $this;
    }

    public function getFinancialDisclosure(): ?FinancialDisclosure
    {
        return $this->financialDisclosure;
    }

    public function setFinancialDisclosure(?FinancialDisclosure $financialDisclosure): static
    {
        $this->financialDisclosure = $financialDisclosure;

        return $this;
    }

    /**
     * @return Collection<int, ParticipatingInvestigator>
     */
    public function getParticipatingInvestigators(): Collection
    {
        return $this->participatingInvestigators;
    }

    public function addParticipatingInvestigator(ParticipatingInvestigator $participatingInvestigator): static
    {
        if (!$this->participatingInvestigators->contains($participatingInvestigator)) {
            $this->participatingInvestigators->add($participatingInvestigator);
            $participatingInvestigator->setApplication($this);
        }

        return $this;
    }

    public function removeParticipatingInvestigator(ParticipatingInvestigator $participatingInvestigator): static
    {
        if ($this->participatingInvestigators->removeElement($participatingInvestigator)) {
            // set the owning side to null (unless already changed)
            if ($participatingInvestigator->getApplication() === $this) {
                $participatingInvestigator->setApplication(null);
            }
        }

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): static
    {
        $this->status = $status;

        return $this;
    }

  
}
