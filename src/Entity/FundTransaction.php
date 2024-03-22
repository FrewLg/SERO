<?php

namespace App\Entity;

use App\Repository\FundTransactionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FundTransactionRepository::class)]
class FundTransaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'fundTransactions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Fund $fundName = null;

    #[ORM\Column(length: 10)]
    private ?string $deducted = null;

    #[ORM\Column(length: 12)]
    private ?string $currentBalance = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $deductedAt = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $reason = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $referenceNubmer = null;

    #[ORM\OneToOne(inversedBy: 'fundTransaction', cascade: ['persist', 'remove'])]
    private ?Training $allotedTo = null;

    #[ORM\ManyToOne(inversedBy: 'fundTransactions')]
    private ?User $createdBy = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFundName(): ?Fund
    {
        return $this->fundName;
    }

    public function setFundName(?Fund $fundName): static
    {
        $this->fundName = $fundName;

        return $this;
    }

    public function getDeducted(): ?string
    {
        return $this->deducted;
    }

    public function setDeducted(string $deducted): static
    {
        $this->deducted = $deducted;

        return $this;
    }

    public function getCurrentBalance(): ?string
    {
        return $this->currentBalance;
    }

    public function setCurrentBalance(string $currentBalance): static
    {
        $this->currentBalance = $currentBalance;

        return $this;
    }

    public function getDeductedAt(): ?\DateTimeInterface
    {
        return $this->deductedAt;
    }

    public function setDeductedAt(\DateTimeInterface $deductedAt): static
    {
        $this->deductedAt = $deductedAt;

        return $this;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(?string $reason): static
    {
        $this->reason = $reason;

        return $this;
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

    public function getReferenceNubmer(): ?string
    {
        return $this->referenceNubmer;
    }

    public function setReferenceNubmer(string $referenceNubmer): static
    {
        $this->referenceNubmer = $referenceNubmer;

        return $this;
    }

    public function getAllotedTo(): ?Training
    {
        return $this->allotedTo;
    }

    public function setAllotedTo(?Training $allotedTo): static
    {
        $this->allotedTo = $allotedTo;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): static
    {
        $this->createdBy = $createdBy;

        return $this;
    }
}
