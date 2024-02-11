<?php

namespace App\Entity;

use App\Repository\TrainingRequestRepository;
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

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $trainingName = null;

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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTrainingName(): ?string
    {
        return $this->trainingName;
    }

    public function setTrainingName(?string $trainingName): static
    {
        $this->trainingName = $trainingName;

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
}
