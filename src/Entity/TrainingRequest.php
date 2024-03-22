<?php

namespace App\Entity;

use App\Repository\TrainingRequestRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: TrainingRequestRepository::class)]
#[Broadcast]
class TrainingRequest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // #[ORM\Column(length: 255, nullable: true)]
    // private ?string $trainingName = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $programDetails = null;

    #[ORM\Column(nullable: true)]
    private ?int $numberOfParticipants = null;

    #[ORM\OneToOne(mappedBy: 'TrainingRequest', cascade: ['persist', 'remove'])]
    private ?Training $training = null;

    #[ORM\ManyToOne(inversedBy: 'trainingRequests')]
    private ?TrainingTopic $trainingTopic = null;

    #[ORM\ManyToMany(targetEntity: Facility::class, inversedBy: 'trainingRequests')]
    private Collection $facility;

    #[ORM\ManyToMany(targetEntity: Partner::class, inversedBy: 'trainingRequests')]
    private Collection $organizer;

    #[ORM\ManyToOne(inversedBy: 'trainingRequests')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $requestedBy = null;

    #[ORM\ManyToOne]
    private ?User $ApprovedBy = null;

    #[ORM\Column(nullable: true)]
    private ?bool $AllowEdit = null;

    #[ORM\ManyToOne(inversedBy: 'trainingRequests')]
    private ?TrainingRequestStatus $status = null;

    #[ORM\ManyToMany(targetEntity: Directorate::class, inversedBy: 'trainingRequests')]
    private Collection $inclusions;
 
    
    public function __construct()
    {
        $this->facility = new ArrayCollection();
        $this->organizer = new ArrayCollection();
        // $this->status = new ArrayCollection();
        $this->inclusions = new ArrayCollection();
      }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getProgramDetails(): ?string
    {
        return $this->programDetails;
    }

    public function setProgramDetails(?string $programDetails): static
    {
        $this->programDetails = $programDetails;

        return $this;
    }

    public function getNumberOfParticipants(): ?int
    {
        return $this->numberOfParticipants;
    }

    public function setNumberOfParticipants(?int $numberOfParticipants): static
    {
        $this->numberOfParticipants = $numberOfParticipants;

        return $this;
    }

    public function getTraining(): ?Training
    {
        return $this->training;
    }

    public function setTraining(?Training $training): static
    {
        // unset the owning side of the relation if necessary
        if ($training === null && $this->training !== null) {
            $this->training->setTrainingRequest(null);
        }

        // set the owning side of the relation if necessary
        if ($training !== null && $training->getTrainingRequest() !== $this) {
            $training->setTrainingRequest($this);
        }

        $this->training = $training;

        return $this;
    }

    public function getTrainingTopic(): ?TrainingTopic
    {
        return $this->trainingTopic;
    }

    public function setTrainingTopic(?TrainingTopic $trainingTopic): static
    {
        $this->trainingTopic = $trainingTopic;

        return $this;
    }

    /**
     * @return Collection<int, Facility>
     */
    public function getFacility(): Collection
    {
        return $this->facility;
    }

    public function addFacility(Facility $facility): static
    {
        if (!$this->facility->contains($facility)) {
            $this->facility->add($facility);
        }

        return $this;
    }

    public function removeFacility(Facility $facility): static
    {
        $this->facility->removeElement($facility);

        return $this;
    }

    public function __toString()
    {
        
   return $this->name;
    }

    /**
     * @return Collection<int, Partner>
     */
    public function getOrganizer(): Collection
    {
        return $this->organizer;
    }

    public function addOrganizer(Partner $organizer): static
    {
        if (!$this->organizer->contains($organizer)) {
            $this->organizer->add($organizer);
        }

        return $this;
    }

    public function removeOrganizer(Partner $organizer): static
    {
        $this->organizer->removeElement($organizer);

        return $this;
    }

    public function getRequestedBy(): ?User
    {
        return $this->requestedBy;
    }

    public function setRequestedBy(?User $requestedBy): static
    {
        $this->requestedBy = $requestedBy;

        return $this;
    }

    public function getApprovedBy(): ?User
    {
        return $this->ApprovedBy;
    }

    public function setApprovedBy(?User $ApprovedBy): static
    {
        $this->ApprovedBy = $ApprovedBy;

        return $this;
    }

    public function isAllowEdit(): ?bool
    {
        return $this->AllowEdit;
    }

    public function setAllowEdit(?bool $AllowEdit): static
    {
        $this->AllowEdit = $AllowEdit;

        return $this;
    }
 

    public function getStatus(): ?TrainingRequestStatus
    {
        return $this->status;
    }

    public function setStatus(?TrainingRequestStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, Directorate>
     */
    public function getInclusions(): Collection
    {
        return $this->inclusions;
    }

    public function addInclusion(Directorate $inclusion): static
    {
        if (!$this->inclusions->contains($inclusion)) {
            $this->inclusions->add($inclusion);
        }

        return $this;
    }

    public function removeInclusion(Directorate $inclusion): static
    {
        $this->inclusions->removeElement($inclusion);

        return $this;
    }

    
  
}
