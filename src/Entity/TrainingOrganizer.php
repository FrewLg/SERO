<?php

namespace App\Entity;

use App\Repository\TrainingOrganizerRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Training;

#[ORM\Entity(repositoryClass: TrainingOrganizerRepository::class)]
class TrainingOrganizer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

   
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'trainingOrganizers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Partner $name = null;

    // #[ORM\ManyToOne(inversedBy: 'organizers')]
    // #[ORM\JoinColumn(nullable: false)]
    // private ?Training $training = null;

  
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

    public function getName(): ?Partner
    {
        return $this->name;
    }

    public function setName(?Partner $name): static
    {
        $this->name = $name;

        return $this;
    }
  
}
