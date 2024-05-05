<?php

namespace App\Entity\SERO;

use App\Entity\SERO\Application;
use App\Entity\SERO\ReviewStatus;
use App\Repository\ApplicationReviewRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ApplicationReviewRepository::class)]
class ApplicationReview
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'applicationReviews')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Application $application = null;

    #[ORM\ManyToOne(inversedBy: 'applicationReviews')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ReviewStatus $review = null;

    #[ORM\Column(nullable: true)]
    private ?bool $checked = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getApplication(): ?Application
    {
        return $this->application;
    }

    public function setApplication(?Application $application): static
    {
        $this->application = $application;

        return $this;
    }

    public function getReview(): ?ReviewStatus
    {
        return $this->review;
    }

    public function setReview(?ReviewStatus $review): static
    {
        $this->review = $review;

        return $this;
    }

    public function isChecked(): ?bool
    {
        return $this->checked;
    }

    public function setChecked(?bool $checked): static
    {
        $this->checked = $checked;

        return $this;
    }
}
