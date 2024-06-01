<?php

namespace App\Entity\SERO;

use App\Repository\SERO\AttachmentTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AttachmentTypeRepository::class)]
class AttachmentType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, Version>
     */
    #[ORM\OneToMany(targetEntity: Version::class, mappedBy: 'attachmentType', orphanRemoval: true)]
    private Collection $versions;

    /**
     * @var Collection<int, Amendment>
     */
    #[ORM\OneToMany(targetEntity: Amendment::class, mappedBy: 'attachmentType')]
    private Collection $amendments;

    /**
     * @var Collection<int, Application>
     */
    #[ORM\OneToMany(targetEntity: Application::class, mappedBy: 'attachmentType')]
    private Collection $applications;

    public function __construct()
    {
        $this->versions = new ArrayCollection();
        $this->amendments = new ArrayCollection();
        $this->applications = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Version>
     */
    public function getVersions(): Collection
    {
        return $this->versions;
    }

    public function addVersion(Version $version): static
    {
        if (!$this->versions->contains($version)) {
            $this->versions->add($version);
            $version->setAttachmentType($this);
        }

        return $this;
    }

    public function removeVersion(Version $version): static
    {
        if ($this->versions->removeElement($version)) {
            // set the owning side to null (unless already changed)
            if ($version->getAttachmentType() === $this) {
                $version->setAttachmentType(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Amendment>
     */
    public function getAmendments(): Collection
    {
        return $this->amendments;
    }

    public function addAmendment(Amendment $amendment): static
    {
        if (!$this->amendments->contains($amendment)) {
            $this->amendments->add($amendment);
            $amendment->setAttachmentType($this);
        }

        return $this;
    }

    public function removeAmendment(Amendment $amendment): static
    {
        if ($this->amendments->removeElement($amendment)) {
            // set the owning side to null (unless already changed)
            if ($amendment->getAttachmentType() === $this) {
                $amendment->setAttachmentType(null);
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
            $application->setAttachmentType($this);
        }

        return $this;
    }

    public function removeApplication(Application $application): static
    {
        if ($this->applications->removeElement($application)) {
            // set the owning side to null (unless already changed)
            if ($application->getAttachmentType() === $this) {
                $application->setAttachmentType(null);
            }
        }

        return $this;
    }
}
