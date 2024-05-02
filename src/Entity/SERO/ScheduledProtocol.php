<?php

namespace App\Entity\SERO;

use App\Repository\SERO\ScheduledProtocolRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ScheduledProtocolRepository::class)]
class ScheduledProtocol
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'scheduledProtocols')]
    private ?Version $protocol = null;

    #[ORM\ManyToOne(inversedBy: 'scheduledProtocols')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Meeting $meeting = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProtocol(): ?Version
    {
        return $this->protocol;
    }

    public function setProtocol(?Version $protocol): static
    {
        $this->protocol = $protocol;

        return $this;
    }

    public function getMeeting(): ?Meeting
    {
        return $this->meeting;
    }

    public function setMeeting(?Meeting $meeting): static
    {
        $this->meeting = $meeting;

        return $this;
    }
}
