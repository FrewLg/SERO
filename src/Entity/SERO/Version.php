<?php

namespace App\Entity\SERO;

use App\Repository\SERO\VersionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VersionRepository::class)]
class Version
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'versions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Application $application = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: Types::TEXT , nullable: true)]
    private ?string $changesMade = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $versionNumber = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $attachment = null;

    #[ORM\ManyToOne(inversedBy: 'version')]
    private ?DecisionType $decisionType = null;

    #[ORM\Column(nullable: true)]
    private ?bool $approved = null;

   
    public function __construct()
    {
        // $this->application = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function __toString()
    {
       return $this->versionNumber;
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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getChangesMade(): ?string
    {
        return $this->changesMade;
    }

    public function setChangesMade(string $changesMade): static
    {
        $this->changesMade = $changesMade;

        return $this;
    }

    public function getVersionNumber(): ?string
    {
        return $this->versionNumber;
    }

    public function setVersionNumber(?string $versionNumber): static
    {
        $this->versionNumber = $versionNumber;

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

    public function getAttachment(): ?string
    {
        return $this->attachment;
    }

    public function setAttachment(?string $attachment): static
    {
        $this->attachment = $attachment;

        return $this;
    }

    public function getDecisionType(): ?DecisionType
    {
        return $this->decisionType;
    }

    public function setDecisionType(?DecisionType $decisionType): static
    {
        $this->decisionType = $decisionType;

        return $this;
    }

    public function isApproved(): ?bool
    {
        return $this->approved;
    }

    public function setApproved(?bool $approved): static
    {
        $this->approved = $approved;

        return $this;
    }
 
}
