<?php

namespace App\Entity;

use App\Repository\TrainingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrainingRepository::class)]
class Training
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $startingDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\OneToOne(inversedBy: 'training', cascade: ['persist', 'remove'])]
    private ?TrainingRequest $TrainingRequest = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    // #[ORM\OneToMany(targetEntity: TrainingOrganizer::class, mappedBy: 'training', orphanRemoval: true)]
    // private Collection $organizers; 
 
   
   
    #[ORM\ManyToOne(inversedBy: 'trainings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Room $venue = null;

    #[ORM\ManyToMany(targetEntity: Modality::class, inversedBy: 'trainings')]
    private Collection $modality;

    #[ORM\OneToMany(targetEntity: TrainingParticipant::class, mappedBy: 'trainings', orphanRemoval: true)]
    private Collection $trainingParticipants;

    
    
    public function __construct()
    {
        // $this->organizers = new ArrayCollection();
        $this->modality = new ArrayCollection();
        $this->trainingParticipants = new ArrayCollection();
     }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getStartingDate(): ?\DateTimeInterface
    {
        return $this->startingDate;
    }

    public function setStartingDate(\DateTimeInterface $startingDate): static
    {
        $this->startingDate = $startingDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): static
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getTrainingRequest(): ?TrainingRequest
    {
        return $this->TrainingRequest;
    }

    public function setTrainingRequest(?TrainingRequest $TrainingRequest): static
    {
        $this->TrainingRequest = $TrainingRequest;

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

    public function setCreatedAt(\DateTimeInterface $createdAt): static
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

    

    public function getVenue(): ?Room
    {
        return $this->venue;
    }

    public function setVenue(Room $venue): static
    {
        $this->venue = $venue;

        return $this;
    }
 

    /**
     * @return Collection<int, Modality>
     */
    public function getModality(): Collection
    {
        return $this->modality;
    }

    public function addModality(Modality $modality): static
    {
        if (!$this->modality->contains($modality)) {
            $this->modality->add($modality);
        }

        return $this;
    }

    public function removeModality(Modality $modality): static
    {
        $this->modality->removeElement($modality);

        return $this;
    }

    /**
     * @return Collection<int, TrainingParticipant>
     */
    public function getTrainingParticipants(): Collection
    {
        return $this->trainingParticipants;
    }

    public function addTrainingParticipant(TrainingParticipant $trainingParticipant): static
    {
        if (!$this->trainingParticipants->contains($trainingParticipant)) {
            $this->trainingParticipants->add($trainingParticipant);
            $trainingParticipant->setTrainings($this);
        }

        return $this;
    }

    public function removeTrainingParticipant(TrainingParticipant $trainingParticipant): static
    {
        if ($this->trainingParticipants->removeElement($trainingParticipant)) {
            // set the owning side to null (unless already changed)
            if ($trainingParticipant->getTrainings() === $this) {
                $trainingParticipant->setTrainings(null);
            }
        }

        return $this;
    }

    
   
}
