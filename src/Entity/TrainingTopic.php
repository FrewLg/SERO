<?php

namespace App\Entity;

use App\Repository\TrainingTopicRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrainingTopicRepository::class)]
class TrainingTopic
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

    #[ORM\OneToMany(targetEntity: TrainingRequest::class, mappedBy: 'trainingTopic')]
    private Collection $trainingRequests;

    #[ORM\OneToMany(targetEntity: Course::class, mappedBy: 'topic')]
    private Collection $courses;

    public function __construct()
    {
        $this->trainingRequests = new ArrayCollection();
        $this->courses = new ArrayCollection();
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
            $trainingRequest->setTrainingTopic($this);
        }

        return $this;
    }

    public function removeTrainingRequest(TrainingRequest $trainingRequest): static
    {
        if ($this->trainingRequests->removeElement($trainingRequest)) {
            // set the owning side to null (unless already changed)
            if ($trainingRequest->getTrainingTopic() === $this) {
                $trainingRequest->setTrainingTopic(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        
   return $this->name;
    }

    /**
     * @return Collection<int, Course>
     */
    public function getCourses(): Collection
    {
        return $this->courses;
    }

    public function addCourse(Course $course): static
    {
        if (!$this->courses->contains($course)) {
            $this->courses->add($course);
            $course->setTopic($this);
        }

        return $this;
    }

    public function removeCourse(Course $course): static
    {
        if ($this->courses->removeElement($course)) {
            // set the owning side to null (unless already changed)
            if ($course->getTopic() === $this) {
                $course->setTopic(null);
            }
        }

        return $this;
    }
    
}
