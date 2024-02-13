<?php

namespace App\Entity;

use App\Repository\TrainingParticipantRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrainingParticipantRepository::class)]
class TrainingParticipant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'trainingParticipants')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Training $trainings = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $voucher = null;

    #[ORM\Column(nullable: true)]
    private ?bool $attended = null;

    #[ORM\Column(nullable: true)]
    private ?bool $certified = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $registeredAt = null;

    #[ORM\Column(nullable: true)]
    private ?int $certId = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $certIssuedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTrainings(): ?Training
    {
        return $this->trainings;
    }

    public function setTrainings(?Training $trainings): static
    {
        $this->trainings = $trainings;

        return $this;
    }

    public function getVoucher(): ?string
    {
        return $this->voucher;
    }

    public function setVoucher(?string $voucher): static
    {
        $this->voucher = $voucher;

        return $this;
    }

    public function isAttended(): ?bool
    {
        return $this->attended;
    }

    public function setAttended(?bool $attended): static
    {
        $this->attended = $attended;

        return $this;
    }

    public function isCertified(): ?bool
    {
        return $this->certified;
    }

    public function setCertified(?bool $certified): static
    {
        $this->certified = $certified;

        return $this;
    }

    public function getRegisteredAt(): ?\DateTimeInterface
    {
        return $this->registeredAt;
    }

    public function setRegisteredAt(?\DateTimeInterface $registeredAt): static
    {
        $this->registeredAt = $registeredAt;

        return $this;
    }

    public function getCertId(): ?int
    {
        return $this->certId;
    }

    public function setCertId(?int $certId): static
    {
        $this->certId = $certId;

        return $this;
    }

    public function getCertIssuedAt(): ?\DateTimeInterface
    {
        return $this->certIssuedAt;
    }

    public function setCertIssuedAt(?\DateTimeInterface $certIssuedAt): static
    {
        $this->certIssuedAt = $certIssuedAt;

        return $this;
    }
}
