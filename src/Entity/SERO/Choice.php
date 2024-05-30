<?php

namespace App\Entity\SERO;

use App\Repository\SERO\ChoiceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChoiceRepository::class)]
class Choice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $choiceName = null;

    #[ORM\ManyToOne(inversedBy: 'answers')]
    private ?ReviewChecklist $checkList = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getChoiceName(): ?string
    {
        return $this->choiceName;
    }

    public function setChoiceName(string $choiceName): static
    {
        $this->choiceName = $choiceName;

        return $this;
    }

    public function getCheckList(): ?ReviewChecklist
    {
        return $this->checkList;
    }
    public function __toString()
    {
       return $this->choiceName;
    }
 
    public function setCheckList(?ReviewChecklist $checkList): static
    {
        $this->checkList = $checkList;

        return $this;
    }
}
