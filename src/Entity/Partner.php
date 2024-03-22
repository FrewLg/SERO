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

    #[ORM\ManyToMany(targetEntity: TrainingRequest::class, mappedBy: 'organizer')]
    private Collection $trainingRequests;

    #[ORM\OneToMany(targetEntity: Fund::class, mappedBy: 'partnerOrganization')]
    private Collection $funds;

    public function __construct()
    {
        $this->trainingRequests = new ArrayCollection();
        $this->funds = new ArrayCollection();
    }

  
   
    // public function __construct()
    // {

        
    // }

    
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
 
    public function __toString()
    {
        
   return $this->name;
    }

    /**
     * @return Collection<int, TrainingRequest>
     */
    public function getTrainingRequests(): Collection
    {
        return $this->trainingRequests;
    }

    public function addTrainingRequest(TrainingRequest $trainingRequest): static
    {
        if (!$this->trainingRequests->contains($trainingRequest)) {
            $this->trainingRequests->add($trainingRequest);
            $trainingRequest->addOrganizer($this);
        }

        return $this;
    }

    public function removeTrainingRequest(TrainingRequest $trainingRequest): static
    {
        if ($this->trainingRequests->removeElement($trainingRequest)) {
            $trainingRequest->removeOrganizer($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Fund>
     */
    public function getFunds(): Collection
    {
        return $this->funds;
    }

    public function addFund(Fund $fund): static
    {
        if (!$this->funds->contains($fund)) {
            $this->funds->add($fund);
            $fund->setPartnerOrganization($this);
        }

        return $this;
    }

    public function removeFund(Fund $fund): static
    {
        if ($this->funds->removeElement($fund)) {
            // set the owning side to null (unless already changed)
            if ($fund->getPartnerOrganization() === $this) {
                $fund->setPartnerOrganization(null);
            }
        }

        return $this;
    }
  

}
