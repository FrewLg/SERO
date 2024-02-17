<?php

namespace App\Entity;

use App\Repository\FundRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FundRepository::class)]
class Fund
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'funds')]
    private ?Partner $partnerOrganization = null;

    #[ORM\Column(length: 16, nullable: true)]
    private ?string $budget = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $amountInWord = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $fiscalYear = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $commencedYear = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $fundEndDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'regFunds')]
    private ?User $createdBy = null;

    #[ORM\OneToMany(targetEntity: FundTransaction::class, mappedBy: 'fundName', orphanRemoval: true)]
    private Collection $fundTransactions;

    #[ORM\ManyToOne(inversedBy: 'grants')]
    private ?Currency $currency = null;

   
    public function __construct()
    {
        $this->fundTransactions = new ArrayCollection();
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

    public function getPartnerOrganization(): ?Partner
    {
        return $this->partnerOrganization;
    }

    public function setPartnerOrganization(?Partner $partnerOrganization): static
    {
        $this->partnerOrganization = $partnerOrganization;

        return $this;
    }

    public function getBudget(): ?string
    {
        return $this->budget;
    }

    public function setBudget(?string $budget): static
    {
        $this->budget = $budget;

        return $this;
    }

    public function getAmountInWord(): ?string
    {
        return $this->amountInWord;
    }

    public function setAmountInWord(?string $amountInWord): static
    {
        $this->amountInWord = $amountInWord;

        return $this;
    }

    public function getFiscalYear(): ?\DateTimeInterface
    {
        return $this->fiscalYear;
    }

    public function setFiscalYear(?\DateTimeInterface $fiscalYear): static
    {
        $this->fiscalYear = $fiscalYear;

        return $this;
    }

    public function getCommencedYear(): ?\DateTimeInterface
    {
        return $this->commencedYear;
    }

    public function setCommencedYear(?\DateTimeInterface $commencedYear): static
    {
        $this->commencedYear = $commencedYear;

        return $this;
    }

    public function getFundEndDate(): ?\DateTimeInterface
    {
        return $this->fundEndDate;
    }

    public function setFundEndDate(?\DateTimeInterface $fundEndDate): static
    {
        $this->fundEndDate = $fundEndDate;

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

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): static
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * @return Collection<int, FundTransaction>
     */
    public function getFundTransactions(): Collection
    {
        return $this->fundTransactions;
    }

    public function addFundTransaction(FundTransaction $fundTransaction): static
    {
        if (!$this->fundTransactions->contains($fundTransaction)) {
            $this->fundTransactions->add($fundTransaction);
            $fundTransaction->setFundName($this);
        }

        return $this;
    }

    public function removeFundTransaction(FundTransaction $fundTransaction): static
    {
        if ($this->fundTransactions->removeElement($fundTransaction)) {
            // set the owning side to null (unless already changed)
            if ($fundTransaction->getFundName() === $this) {
                $fundTransaction->setFundName(null);
            }
        }

        return $this;
    }

    public function getCurrency(): ?Currency
    {
        return $this->currency;
    }

    public function setCurrency(?Currency $currency): static
    {
        $this->currency = $currency;

        return $this;
    }

   
}
