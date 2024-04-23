<?php

namespace App\Entity\SERO;

use App\Repository\SERO\DecisionTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DecisionTypeRepository::class)]
class DecisionType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $createdAt = null;

    /**
     * @var Collection<int, Version>
     */
    #[ORM\OneToMany(targetEntity: Version::class, mappedBy: 'decisionType')]
    private Collection $version;

    public function __construct()
    {
        $this->version = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

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

    /**
     * @return Collection<int, Version>
     */
    public function getVersion(): Collection
    {
        return $this->version;
    }

    public function addVersion(Version $version): static
    {
        if (!$this->version->contains($version)) {
            $this->version->add($version);
            $version->setDecisionType($this);
        }

        return $this;
    }

    public function removeVersion(Version $version): static
    {
        if ($this->version->removeElement($version)) {
            // set the owning side to null (unless already changed)
            if ($version->getDecisionType() === $this) {
                $version->setDecisionType(null);
            }
        }

        return $this;
    }
}
