<?php

namespace App\Entity\SERO;

use App\Repository\SERO\ReviewFormRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReviewFormRepository::class)]
class ReviewForm
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

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    /**
     * @var Collection<int, ReviewChecklist>
     */
    #[ORM\OneToMany(targetEntity: ReviewChecklist::class, mappedBy: 'reviewForm')]
    private Collection $reviewChecklists;

    #[ORM\Column(nullable: true)]
    private ?bool $active = null;

    public function __construct()
    {
        $this->reviewChecklists = new ArrayCollection();
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

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, ReviewChecklist>
     */
    public function getReviewChecklists(): Collection
    {
        return $this->reviewChecklists;
    }

    public function addReviewChecklist(ReviewChecklist $reviewChecklist): static
    {
        if (!$this->reviewChecklists->contains($reviewChecklist)) {
            $this->reviewChecklists->add($reviewChecklist);
            $reviewChecklist->setReviewForm($this);
        }

        return $this;
    }

    public function removeReviewChecklist(ReviewChecklist $reviewChecklist): static
    {
        if ($this->reviewChecklists->removeElement($reviewChecklist)) {
            // set the owning side to null (unless already changed)
            if ($reviewChecklist->getReviewForm() === $this) {
                $reviewChecklist->setReviewForm(null);
            }
        }

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(?bool $active): static
    {
        $this->active = $active;

        return $this;
    }
}
