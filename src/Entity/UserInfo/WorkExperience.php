<?php

namespace App\Entity\UserInfo;

use App\Entity\Profile;
use App\Repository\UserInfo\WorkExperienceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WorkExperienceRepository::class)]
class WorkExperience
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $orgName = null;

    #[ORM\Column(length: 255)]
    private ?string $jobPosition = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateStarted = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateLeft = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $responsibilities = null;

    #[ORM\ManyToOne(inversedBy: 'workExperiences')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Profile $profile = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrgName(): ?string
    {
        return $this->orgName;
    }

    public function __toString()
    {
        
   return $this->orgName;
    }
    public function setOrgName(string $orgName): static
    {
        $this->orgName = $orgName;

        return $this;
    }

    public function getJobPosition(): ?string
    {
        return $this->jobPosition;
    }

    public function setJobPosition(string $jobPosition): static
    {
        $this->jobPosition = $jobPosition;

        return $this;
    }

    public function getDateStarted(): ?\DateTimeInterface
    {
        return $this->dateStarted;
    }

    public function setDateStarted(?\DateTimeInterface $dateStarted): static
    {
        $this->dateStarted = $dateStarted;

        return $this;
    }

    public function getDateLeft(): ?\DateTimeInterface
    {
        return $this->dateLeft;
    }

    public function setDateLeft(?\DateTimeInterface $dateLeft): static
    {
        $this->dateLeft = $dateLeft;

        return $this;
    }

    public function getResponsibilities(): ?string
    {
        return $this->responsibilities;
    }

    public function setResponsibilities(?string $responsibilities): static
    {
        $this->responsibilities = $responsibilities;

        return $this;
    }

    public function getProfile(): ?Profile
    {
        return $this->profile;
    }

    public function setProfile(?Profile $profile): static
    {
        $this->profile = $profile;

        return $this;
    }
}
