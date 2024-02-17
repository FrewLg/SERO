<?php

namespace App\Entity;

use App\Repository\CurrencyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CurrencyRepository::class)]
class Currency
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $abbreviation = null;

    #[ORM\OneToMany(targetEntity: Fund::class, mappedBy: 'currency')]
    private Collection $grants;

    public function __construct()
    {
        $this->grants = new ArrayCollection();
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

    public function getAbbreviation(): ?string
    {
        return $this->abbreviation;
    }

    public function setAbbreviation(?string $abbreviation): static
    {
        $this->abbreviation = $abbreviation;

        return $this;
    }

    public function __toString()
    {
        
   return $this->abbreviation;
    }
    /**
     * @return Collection<int, Fund>
     */
    public function getGrants(): Collection
    {
        return $this->grants;
    }

    public function addGrant(Fund $grant): static
    {
        if (!$this->grants->contains($grant)) {
            $this->grants->add($grant);
            $grant->setCurrency($this);
        }

        return $this;
    }

    public function removeGrant(Fund $grant): static
    {
        if ($this->grants->removeElement($grant)) {
            // set the owning side to null (unless already changed)
            if ($grant->getCurrency() === $this) {
                $grant->setCurrency(null);
            }
        }

        return $this;
    }
}
