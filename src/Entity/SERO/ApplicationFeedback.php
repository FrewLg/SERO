<?php

namespace App\Entity\SERO;

use App\Entity\User;
use App\Repository\SERO\ApplicationFeedbackRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ApplicationFeedbackRepository::class)]
class ApplicationFeedback
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'applicationFeedback')]
    private ?User $feedbackFrom = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?bool $sendMail = null;

    #[ORM\Column(nullable: true)]
    private ?bool $allowWrite = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $attachment = null;

    #[ORM\ManyToOne(inversedBy: 'applicationFeedback')]
    private ?Application $application = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFeedbackFrom(): ?User
    {
        return $this->feedbackFrom;
    }

    public function setFeedbackFrom(?User $feedbackFrom): static
    {
        $this->feedbackFrom = $feedbackFrom;

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

    public function isSendMail(): ?bool
    {
        return $this->sendMail;
    }

    public function setSendMail(?bool $sendMail): static
    {
        $this->sendMail = $sendMail;

        return $this;
    }

    public function isAllowWrite(): ?bool
    {
        return $this->allowWrite;
    }

    public function setAllowWrite(?bool $allowWrite): static
    {
        $this->allowWrite = $allowWrite;

        return $this;
    }

    public function getAttachment(): ?string
    {
        return $this->attachment;
    }

    public function setAttachment(?string $attachment): static
    {
        $this->attachment = $attachment;

        return $this;
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
}
