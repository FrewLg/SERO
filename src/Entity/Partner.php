<?php

namespace App\Entity;

use App\Repository\PartnerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PartnerRepository::class)]
class Partner
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $logo = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\OneToMany(targetEntity: TrainingOrganizer::class, mappedBy: 'name', orphanRemoval: true)]
    private Collection $trainingOrganizers;

    public function __construct()
    {
        $this->trainingOrganizers = new ArrayCollection();
    }

    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

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

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): static
    {
        $this->logo = $logo;

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

    /**
     * @return Collection<int, TrainingOrganizer>
     */
    public function getTrainingOrganizers(): Collection
    {
        return $this->trainingOrganizers;
    }

    public function addTrainingOrganizer(TrainingOrganizer $trainingOrganizer): static
    {
        if (!$this->trainingOrganizers->contains($trainingOrganizer)) {
            $this->trainingOrganizers->add($trainingOrganizer);
            $trainingOrganizer->setName($this);
        }

        return $this;
    }

    public function removeTrainingOrganizer(TrainingOrganizer $trainingOrganizer): static
    {
        if ($this->trainingOrganizers->removeElement($trainingOrganizer)) {
            // set the owning side to null (unless already changed)
            if ($trainingOrganizer->getName() === $this) {
                $trainingOrganizer->setName(null);
            }
        }

        return $this;
    }
 
}
