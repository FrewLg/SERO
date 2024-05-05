<?php

namespace App\Entity\SERO;

use App\Repository\ReviewStatusGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReviewStatusGroupRepository::class)]
class ReviewStatusGroup
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    /**
     * @var Collection<int, ReviewStatus>
     */
    #[ORM\OneToMany(targetEntity: ReviewStatus::class, mappedBy: 'reviewGroup', orphanRemoval: true)]
    private Collection $reviewStatuses;

    public function __construct()
    {
        $this->reviewStatuses = new ArrayCollection();
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

    /**
     * @return Collection<int, ReviewStatus>
     */
    public function getReviewStatuses(): Collection
    {
        return $this->reviewStatuses;
    }

    public function addReviewStatus(ReviewStatus $reviewStatus): static
    {
        if (!$this->reviewStatuses->contains($reviewStatus)) {
            $this->reviewStatuses->add($reviewStatus);
            $reviewStatus->setReviewGroup($this);
        }

        return $this;
    }

    public function removeReviewStatus(ReviewStatus $reviewStatus): static
    {
        if ($this->reviewStatuses->removeElement($reviewStatus)) {
            // set the owning side to null (unless already changed)
            if ($reviewStatus->getReviewGroup() === $this) {
                $reviewStatus->setReviewGroup(null);
            }
        }

        return $this;
    }
}
