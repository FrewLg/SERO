<?php

namespace App\Entity;

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

    #[ORM\OneToMany(targetEntity: TrainingMaterial::class, mappedBy: 'uploadedBy', orphanRemoval: true)]
    private Collection $trainingMaterials;

    #[ORM\OneToMany(targetEntity: Feedback::class, mappedBy: 'sentBy')]
    private Collection $feedback;

    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?Directorate $directorate = null;

    #[ORM\OneToMany(targetEntity: TrainingRequest::class, mappedBy: 'requestedBy', orphanRemoval: true)]
    private Collection $trainingRequests;

    #[ORM\OneToMany(targetEntity: Course::class, mappedBy: 'createdBy')]
    private Collection $preparedCourses;

  
    public function __construct()
    {
        $this->trainingMaterials = new ArrayCollection();
        $this->feedback = new ArrayCollection();
        $this->trainingRequests = new ArrayCollection();
        $this->preparedCourses = new ArrayCollection();
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

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

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
     * @return Collection<int, TrainingMaterial>
     */
    public function getTrainingMaterials(): Collection
    {
        return $this->trainingMaterials;
    }

    public function addTrainingMaterial(TrainingMaterial $trainingMaterial): static
    {
        if (!$this->trainingMaterials->contains($trainingMaterial)) {
            $this->trainingMaterials->add($trainingMaterial);
            $trainingMaterial->setUploadedBy($this);
        }

        return $this;
    }

    public function removeTrainingMaterial(TrainingMaterial $trainingMaterial): static
    {
        if ($this->trainingMaterials->removeElement($trainingMaterial)) {
            // set the owning side to null (unless already changed)
            if ($trainingMaterial->getUploadedBy() === $this) {
                $trainingMaterial->setUploadedBy(null);
            }
        }

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
     * @return Collection<int, TrainingRequest>
     */
    public function getTrainingRequests(): Collection
    {
        return $this->trainingRequests;
    }

    public function addTrainingRequest(TrainingRequest $trainingRequest): static
    {
        if (!$this->trainingRequests->contains($trainingRequest)) {
            $this->trainingRequests->add($trainingRequest);
            $trainingRequest->setRequestedBy($this);
        }

        return $this;
    }

    public function removeTrainingRequest(TrainingRequest $trainingRequest): static
    {
        if ($this->trainingRequests->removeElement($trainingRequest)) {
            // set the owning side to null (unless already changed)
            if ($trainingRequest->getRequestedBy() === $this) {
                $trainingRequest->setRequestedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Course>
     */
    public function getPreparedCourses(): Collection
    {
        return $this->preparedCourses;
    }

    public function addPreparedCourse(Course $preparedCourse): static
    {
        if (!$this->preparedCourses->contains($preparedCourse)) {
            $this->preparedCourses->add($preparedCourse);
            $preparedCourse->setCreatedBy($this);
        }

        return $this;
    }

    public function removePreparedCourse(Course $preparedCourse): static
    {
        if ($this->preparedCourses->removeElement($preparedCourse)) {
            // set the owning side to null (unless already changed)
            if ($preparedCourse->getCreatedBy() === $this) {
                $preparedCourse->setCreatedBy(null);
            }
        }

        return $this;
    }

    
}
