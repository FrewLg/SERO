<?php

namespace App\Entity\SERO;

use App\Repository\SERO\ReviewChecklistGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReviewChecklistGroupRepository::class)]
class ReviewChecklistGroup
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, ReviewChecklist>
     */
    #[ORM\OneToMany(targetEntity: ReviewChecklist::class, mappedBy: 'checklistGroup', orphanRemoval: true)]
    private Collection $reviewChecklists;

    public function __construct()
    {
        $this->reviewChecklists = new ArrayCollection();
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
     * @return Collection<int, ReviewChecklist>
     */
    public function getReviewChecklists(): Collection
    {
        return $this->reviewChecklists;
    }

    public function addReviewChecklist(ReviewChecklist $reviewChecklist): static
    {
        if (!$this->reviewChecklists->contains($reviewChecklist)) {
            $this->reviewChecklists->add($reviewChecklist);
            $reviewChecklist->setChecklistGroup($this);
        }

        return $this;
    }
    public function __toString()
    {
            return $this->name;
    }

    public function removeReviewChecklist(ReviewChecklist $reviewChecklist): static
    {
        if ($this->reviewChecklists->removeElement($reviewChecklist)) {
            // set the owning side to null (unless already changed)
            if ($reviewChecklist->getChecklistGroup() === $this) {
                $reviewChecklist->setChecklistGroup(null);
            }
        }

        return $this;
    }
}
