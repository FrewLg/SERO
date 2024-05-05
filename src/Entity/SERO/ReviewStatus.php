<?php

namespace App\Entity\SERO;

use App\Entity\SERO\ApplicationReview;
use App\Repository\ReviewStatusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReviewStatusRepository::class)]
class ReviewStatus
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'reviewStatuses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ReviewStatusGroup $reviewGroup = null;

    /**
     * @var Collection<int, ApplicationReview>
     */
    #[ORM\OneToMany(targetEntity: ApplicationReview::class, mappedBy: 'review', orphanRemoval: true)]
    private Collection $applicationReviews;

    public function __construct()
    {
        $this->applicationReviews = new ArrayCollection();
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

    public function getReviewGroup(): ?ReviewStatusGroup
    {
        return $this->reviewGroup;
    }

    public function setReviewGroup(?ReviewStatusGroup $reviewGroup): static
    {
        $this->reviewGroup = $reviewGroup;

        return $this;
    }

    /**
     * @return Collection<int, ApplicationReview>
     */
    public function getApplicationReviews(): Collection
    {
        return $this->applicationReviews;
    }

    public function addApplicationReview(ApplicationReview $applicationReview): static
    {
        if (!$this->applicationReviews->contains($applicationReview)) {
            $this->applicationReviews->add($applicationReview);
            $applicationReview->setReview($this);
        }

        return $this;
    }

    public function removeApplicationReview(ApplicationReview $applicationReview): static
    {
        if ($this->applicationReviews->removeElement($applicationReview)) {
            // set the owning side to null (unless already changed)
            if ($applicationReview->getReview() === $this) {
                $applicationReview->setReview(null);
            }
        }

        return $this;
    }
}
