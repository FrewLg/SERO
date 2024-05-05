<?php

namespace App\Entity;

use App\Entity\SERO\Application;
use App\Entity\SERO\ApplicationFeedback;
use App\Entity\SERO\BoardMember;
use App\Entity\SERO\Continuation;
use App\Entity\SERO\IrbCertificate;
use App\Entity\SERO\Meeting;
use App\Entity\SERO\MeetingSchedule;
use App\Entity\SERO\ReviewAssignment;
use App\Entity\SERO\ReviewerResponse;
use App\Entity\SERO\ReviewersPool;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?Profile $profile = null;

  
    #[ORM\OneToMany(targetEntity: Feedback::class, mappedBy: 'sentBy')]
    private Collection $feedback;

    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?Directorate $directorate = null;

   
    #[ORM\ManyToMany(targetEntity: UserGroup::class, mappedBy: 'users')]
    private Collection $userGroup;

    #[ORM\ManyToMany(targetEntity: Permission::class, inversedBy: 'users')]
    private Collection $permissions;
 

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $locale = null;

    /**
     * @var Collection<int, ReviewAssignment>
     */
    #[ORM\OneToMany(targetEntity: ReviewAssignment::class, mappedBy: 'irbreviewer')]
    private Collection $reviewAssignments;

    /**
     * @var Collection<int, BoardMember>
     */
    #[ORM\OneToMany(targetEntity: BoardMember::class, mappedBy: 'assignedBy')]
    private Collection $boardMembers;

    /**
     * @var Collection<int, IrbCertificate>
     */
    #[ORM\OneToMany(targetEntity: IrbCertificate::class, mappedBy: 'approvedBy')]
    private Collection $irbCertificates;

    /**
     * @var Collection<int, Meeting>
     */
    #[ORM\OneToMany(targetEntity: Meeting::class, mappedBy: 'createdBy')]
    private Collection $meetings;

    /**
     * @var Collection<int, ApplicationFeedback>
     */
    #[ORM\OneToMany(targetEntity: ApplicationFeedback::class, mappedBy: 'feedbackFrom')]
    private Collection $applicationFeedback;

    /**
     * @var Collection<int, Application>
     */
    #[ORM\OneToMany(targetEntity: Application::class, mappedBy: 'submittedBy', orphanRemoval: true)]
    private Collection $applications;

    /**
     * @var Collection<int, ReviewerResponse>
     */
    #[ORM\OneToMany(targetEntity: ReviewerResponse::class, mappedBy: 'reviewedBy')]
    private Collection $reviewerResponses;

    /**
     * @var Collection<int, MeetingSchedule>
     */
    #[ORM\OneToMany(targetEntity: MeetingSchedule::class, mappedBy: 'createdBy')]
    private Collection $meetingSchedules;

    /**
     * @var Collection<int, Continuation>
     */
    #[ORM\OneToMany(targetEntity: Continuation::class, mappedBy: 'requestedBy')]
    private Collection $continuations;

    /**
     * @var Collection<int, Continuation>
     */
    #[ORM\OneToMany(targetEntity: Continuation::class, mappedBy: 'approvedBy')]
    private Collection $continuationApprovals;

    /**
     * @var Collection<int, ReviewersPool>
     */
    #[ORM\OneToMany(targetEntity: ReviewersPool::class, mappedBy: 'user')]
    private Collection $reviewersPools;

  
    public function __construct()
    {
        $this->userGroup = new ArrayCollection();
        $this->permissions = new ArrayCollection();
         $this->reviewAssignments = new ArrayCollection();
        $this->boardMembers = new ArrayCollection();
        $this->irbCertificates = new ArrayCollection();
        $this->meetings = new ArrayCollection();
        $this->applicationFeedback = new ArrayCollection();
        $this->applications = new ArrayCollection();
        $this->reviewerResponses = new ArrayCollection();
        $this->meetingSchedules = new ArrayCollection();
        $this->continuations = new ArrayCollection();
        $this->continuationApprovals = new ArrayCollection();
        $this->reviewersPools = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function addRole(string $role): self
    {
        $roles = $this->roles; 
        $roles[] = $role; 

       
        $this->setRoles(array_unique($roles));
        

        return $this;
    }
    
    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = "ROLE_USER";

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;
        // $roles[] = "ROLE_USER";

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getProfile(): ?Profile
    {
        return $this->profile;
    }

    public function __toString()
    {
        
   return  "".$this->profile;
    }


    public function setProfile(?Profile $profile): static
    {
        // unset the owning side of the relation if necessary
        if ($profile === null && $this->profile !== null) {
            $this->profile->setUser(null);
        }

        // set the owning side of the relation if necessary
        if ($profile !== null && $profile->getUser() !== $this) {
            $profile->setUser($this);
        }

        $this->profile = $profile;

        return $this;
    }

    

    /**
     * @return Collection<int, Feedback>
     */
    public function getFeedback(): Collection
    {
        return $this->feedback;
    }

    public function addFeedback(Feedback $feedback): static
    {
        if (!$this->feedback->contains($feedback)) {
            $this->feedback->add($feedback);
            $feedback->setSentBy($this);
        }

        return $this;
    }

    public function removeFeedback(Feedback $feedback): static
    {
        if ($this->feedback->removeElement($feedback)) {
            // set the owning side to null (unless already changed)
            if ($feedback->getSentBy() === $this) {
                $feedback->setSentBy(null);
            }
        }

        return $this;
    }

    public function getDirectorate(): ?Directorate
    {
        return $this->directorate;
    }

    public function setDirectorate(?Directorate $directorate): static
    {
        $this->directorate = $directorate;

        return $this;
    }

   

    /**
     * @return Collection<int, UserGroup>
     */
    public function getUserGroup(): Collection
    {
        return $this->userGroup;
    }

    public function addUserGroup(UserGroup $userGroup): static
    {
        if (!$this->userGroup->contains($userGroup)) {
            $this->userGroup->add($userGroup);
            $userGroup->addUser($this);
        }

        return $this;
    }

    public function removeUserGroup(UserGroup $userGroup): static
    {
        if ($this->userGroup->removeElement($userGroup)) {
            $userGroup->removeUser($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Permission>
     */
    public function getPermissions(): Collection
    {
        return $this->permissions;
    }

    public function addPermission(Permission $permission): static
    {
        if (!$this->permissions->contains($permission)) {
            $this->permissions->add($permission);
        }

        return $this;
    }

    public function removePermission(Permission $permission): static
    {
        $this->permissions->removeElement($permission);

        return $this;
    }
 
    public function getLocale(): ?string
    {
        return $this->locale;
    }

    public function setLocale(?string $locale): static
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * @return Collection<int, ReviewAssignment>
     */
    public function getReviewAssignments(): Collection
    {
        return $this->reviewAssignments;
    }

    public function addReviewAssignment(ReviewAssignment $reviewAssignment): static
    {
        if (!$this->reviewAssignments->contains($reviewAssignment)) {
            $this->reviewAssignments->add($reviewAssignment);
            $reviewAssignment->setIrbreviewer($this);
        }

        return $this;
    }

    public function removeReviewAssignment(ReviewAssignment $reviewAssignment): static
    {
        if ($this->reviewAssignments->removeElement($reviewAssignment)) {
            // set the owning side to null (unless already changed)
            if ($reviewAssignment->getIrbreviewer() === $this) {
                $reviewAssignment->setIrbreviewer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, BoardMember>
     */
    public function getBoardMembers(): Collection
    {
        return $this->boardMembers;
    }

    public function addBoardMember(BoardMember $boardMember): static
    {
        if (!$this->boardMembers->contains($boardMember)) {
            $this->boardMembers->add($boardMember);
            $boardMember->setAssignedBy($this);
        }

        return $this;
    }

    public function removeBoardMember(BoardMember $boardMember): static
    {
        if ($this->boardMembers->removeElement($boardMember)) {
            // set the owning side to null (unless already changed)
            if ($boardMember->getAssignedBy() === $this) {
                $boardMember->setAssignedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, IrbCertificate>
     */
    public function getIrbCertificates(): Collection
    {
        return $this->irbCertificates;
    }

    public function addIrbCertificate(IrbCertificate $irbCertificate): static
    {
        if (!$this->irbCertificates->contains($irbCertificate)) {
            $this->irbCertificates->add($irbCertificate);
            $irbCertificate->setApprovedBy($this);
        }

        return $this;
    }

    public function removeIrbCertificate(IrbCertificate $irbCertificate): static
    {
        if ($this->irbCertificates->removeElement($irbCertificate)) {
            // set the owning side to null (unless already changed)
            if ($irbCertificate->getApprovedBy() === $this) {
                $irbCertificate->setApprovedBy(null);
            }
        }

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
            $meeting->setCreatedBy($this);
        }

        return $this;
    }

    public function removeMeeting(Meeting $meeting): static
    {
        if ($this->meetings->removeElement($meeting)) {
            // set the owning side to null (unless already changed)
            if ($meeting->getCreatedBy() === $this) {
                $meeting->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ApplicationFeedback>
     */
    public function getApplicationFeedback(): Collection
    {
        return $this->applicationFeedback;
    }

    public function addApplicationFeedback(ApplicationFeedback $applicationFeedback): static
    {
        if (!$this->applicationFeedback->contains($applicationFeedback)) {
            $this->applicationFeedback->add($applicationFeedback);
            $applicationFeedback->setFeedbackFrom($this);
        }

        return $this;
    }

    public function removeApplicationFeedback(ApplicationFeedback $applicationFeedback): static
    {
        if ($this->applicationFeedback->removeElement($applicationFeedback)) {
            // set the owning side to null (unless already changed)
            if ($applicationFeedback->getFeedbackFrom() === $this) {
                $applicationFeedback->setFeedbackFrom(null);
            }
        }

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
            $application->setSubmittedBy($this);
        }

        return $this;
    }

    public function removeApplication(Application $application): static
    {
        if ($this->applications->removeElement($application)) {
            // set the owning side to null (unless already changed)
            if ($application->getSubmittedBy() === $this) {
                $application->setSubmittedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ReviewerResponse>
     */
    public function getReviewerResponses(): Collection
    {
        return $this->reviewerResponses;
    }

    public function addReviewerResponse(ReviewerResponse $reviewerResponse): static
    {
        if (!$this->reviewerResponses->contains($reviewerResponse)) {
            $this->reviewerResponses->add($reviewerResponse);
            $reviewerResponse->setReviewedBy($this);
        }

        return $this;
    }

    public function removeReviewerResponse(ReviewerResponse $reviewerResponse): static
    {
        if ($this->reviewerResponses->removeElement($reviewerResponse)) {
            // set the owning side to null (unless already changed)
            if ($reviewerResponse->getReviewedBy() === $this) {
                $reviewerResponse->setReviewedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, MeetingSchedule>
     */
    public function getMeetingSchedules(): Collection
    {
        return $this->meetingSchedules;
    }

    public function addMeetingSchedule(MeetingSchedule $meetingSchedule): static
    {
        if (!$this->meetingSchedules->contains($meetingSchedule)) {
            $this->meetingSchedules->add($meetingSchedule);
            $meetingSchedule->setCreatedBy($this);
        }

        return $this;
    }

    public function removeMeetingSchedule(MeetingSchedule $meetingSchedule): static
    {
        if ($this->meetingSchedules->removeElement($meetingSchedule)) {
            // set the owning side to null (unless already changed)
            if ($meetingSchedule->getCreatedBy() === $this) {
                $meetingSchedule->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Continuation>
     */
    public function getContinuations(): Collection
    {
        return $this->continuations;
    }

    public function addContinuation(Continuation $continuation): static
    {
        if (!$this->continuations->contains($continuation)) {
            $this->continuations->add($continuation);
            $continuation->setRequestedBy($this);
        }

        return $this;
    }

    public function removeContinuation(Continuation $continuation): static
    {
        if ($this->continuations->removeElement($continuation)) {
            // set the owning side to null (unless already changed)
            if ($continuation->getRequestedBy() === $this) {
                $continuation->setRequestedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Continuation>
     */
    public function getContinuationApprovals(): Collection
    {
        return $this->continuationApprovals;
    }

    public function addContinuationApproval(Continuation $continuationApproval): static
    {
        if (!$this->continuationApprovals->contains($continuationApproval)) {
            $this->continuationApprovals->add($continuationApproval);
            $continuationApproval->setApprovedBy($this);
        }

        return $this;
    }

    public function removeContinuationApproval(Continuation $continuationApproval): static
    {
        if ($this->continuationApprovals->removeElement($continuationApproval)) {
            // set the owning side to null (unless already changed)
            if ($continuationApproval->getApprovedBy() === $this) {
                $continuationApproval->setApprovedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ReviewersPool>
     */
    public function getReviewersPools(): Collection
    {
        return $this->reviewersPools;
    }

    public function addReviewersPool(ReviewersPool $reviewersPool): static
    {
        if (!$this->reviewersPools->contains($reviewersPool)) {
            $this->reviewersPools->add($reviewersPool);
            $reviewersPool->setUser($this);
        }

        return $this;
    }

    public function removeReviewersPool(ReviewersPool $reviewersPool): static
    {
        if ($this->reviewersPools->removeElement($reviewersPool)) {
            // set the owning side to null (unless already changed)
            if ($reviewersPool->getUser() === $this) {
                $reviewersPool->setUser(null);
            }
        }

        return $this;
    }

    
}
