<?php

namespace App\Entity\SERO;

use App\Entity\User;
use App\Repository\SERO\BoardMemberRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BoardMemberRepository::class)]
class BoardMember
{
    public   $statuses = [
        1 => "Active",
        2 => "Deactivated"
    ];
    const ROLE_CHAIR = 'ROLE_CHAIR';
    const ROLE_VICE_CHAIR = 'ROLE_VICE_CHAIR';
    const ROLE_SECRETARY = 'ROLE_SECRETARY';
    const ROLE_COORDINATOR = 'ROLE_COORDINATOR';
    const ROLE_MEMBER = 'ROLE_MEMBER';


    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist' ])]
    // #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?User $user = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $assignedAt = null;

    #[ORM\ManyToOne(inversedBy: 'boardMembers')]
    private ?User $assignedBy = null;

    #[ORM\Column(nullable: true)]
    private ?int $status = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $role = null;

    /**
     * @var Collection<int, Meeting>
     */
    #[ORM\ManyToMany(targetEntity: Meeting::class, mappedBy: 'attendee')]
    private Collection $meetings;

    public function __construct()
    {
        $this->meetings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getAssignedAt(): ?\DateTimeInterface
    {
        return $this->assignedAt;
    }

    public function setAssignedAt(?\DateTimeInterface $assignedAt): static
    {
        $this->assignedAt = $assignedAt;

        return $this;
    }

    public function getAssignedBy(): ?User
    {
        return $this->assignedBy;
    }

    public function setAssignedBy(?User $assignedBy): static
    {
        $this->assignedBy = $assignedBy;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(?int $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(?string $role): static
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @return Collection<int, Meeting>
     */
    public function getMeetings(): Collection
    {
        return $this->meetings;
    }

    public function addMeeting(Meeting $meeting): static
    {
        if (!$this->meetings->contains($meeting)) {
            $this->meetings->add($meeting);
            $meeting->addAttendee($this);
        }

        return $this;
    }

    public function removeMeeting(Meeting $meeting): static
    {
        if ($this->meetings->removeElement($meeting)) {
            $meeting->removeAttendee($this);
        }

        return $this;
    }
}
