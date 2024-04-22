<?php

namespace App\Entity\SERO;

use App\Entity\User;
use App\Repository\SERO\MeetingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MeetingRepository::class)]
class Meeting
{
    const STATUS_ACTIVE=1;
    const STATUS_CLOSED=2;
    const STATUS_SCHEDULED=3;
    const messages=[
        self::STATUS_ACTIVE=>"Active",
        self::STATUS_CLOSED=>"Closed",
        self::STATUS_SCHEDULED=>"Scheduled",
    ];
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $number = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $heldAt = null;

    #[ORM\ManyToOne(inversedBy: 'meetings')]
    private ?User $createdBy = null;

    /**
     * @var Collection<int, BoardMember>
     */
    #[ORM\ManyToMany(targetEntity: BoardMember::class, inversedBy: 'meetings')]
    private Collection $attendee;

    #[ORM\Column]
    private ?int $status = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $note = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $minuteTakenAt = null;

    #[ORM\ManyToOne(inversedBy: 'meetings')]
    private ?User $minuteTakenBy = null;

    /**
     * @var Collection<int, Application>
     */
    #[ORM\OneToMany(targetEntity: Application::class, mappedBy: 'meeting')]
    private Collection $applications;

    #[ORM\ManyToOne(inversedBy: 'meetings')]
    private ?MeetingSchedule $meetingSchedule = null;

    public function __construct()
    {
        $this->attendee = new ArrayCollection();
        $this->applications = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(?string $number): static
    {
        $this->number = $number;

        return $this;
    }

    public function getHeldAt(): ?\DateTimeInterface
    {
        return $this->heldAt;
    }

    public function setHeldAt(?\DateTimeInterface $heldAt): static
    {
        $this->heldAt = $heldAt;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): static
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * @return Collection<int, BoardMember>
     */
    public function getAttendee(): Collection
    {
        return $this->attendee;
    }

    public function addAttendee(BoardMember $attendee): static
    {
        if (!$this->attendee->contains($attendee)) {
            $this->attendee->add($attendee);
        }

        return $this;
    }

    public function removeAttendee(BoardMember $attendee): static
    {
        $this->attendee->removeElement($attendee);

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): static
    {
        $this->note = $note;

        return $this;
    }

    public function getMinuteTakenAt(): ?\DateTimeInterface
    {
        return $this->minuteTakenAt;
    }

    public function setMinuteTakenAt(?\DateTimeInterface $minuteTakenAt): static
    {
        $this->minuteTakenAt = $minuteTakenAt;

        return $this;
    }

    public function getMinuteTakenBy(): ?User
    {
        return $this->minuteTakenBy;
    }

    public function setMinuteTakenBy(?User $minuteTakenBy): static
    {
        $this->minuteTakenBy = $minuteTakenBy;

        return $this;
    }

    /**
     * @return Collection<int, Application>
     */
    public function getApplications(): Collection
    {
        return $this->applications;
    }

    public function addApplication(Application $application): static
    {
        if (!$this->applications->contains($application)) {
            $this->applications->add($application);
            $application->setMeeting($this);
        }

        return $this;
    }

    public function removeApplication(Application $application): static
    {
        if ($this->applications->removeElement($application)) {
            // set the owning side to null (unless already changed)
            if ($application->getMeeting() === $this) {
                $application->setMeeting(null);
            }
        }

        return $this;
    }

    public function getMeetingSchedule(): ?MeetingSchedule
    {
        return $this->meetingSchedule;
    }

    public function setMeetingSchedule(?MeetingSchedule $meetingSchedule): static
    {
        $this->meetingSchedule = $meetingSchedule;

        return $this;
    }
}
