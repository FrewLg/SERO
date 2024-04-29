<?php

namespace App\Entity\SERO;

use App\Repository\SERO\AmendmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AmendmentRepository::class)]
class Amendment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

  
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $purpose = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $changes = null;

    #[ORM\ManyToOne(inversedBy: 'amendments')]
    private ?Version $version = null;

  
    #[ORM\ManyToOne(inversedBy: 'amendments')]
    private ?AttachmentType $attachmentType = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $attachment = null;

    
    public function getId(): ?int
    {
        return $this->id;
    }
 

    public function getPurpose(): ?string
    {
        return $this->purpose;
    }

    public function setPurpose(?string $purpose): static
    {
        $this->purpose = $purpose;

        return $this;
    }

    public function getChanges(): ?string
    {
        return $this->changes;
    }

    public function setChanges(?string $changes): static
    {
        $this->changes = $changes;

        return $this;
    }

    public function getVersion(): ?Version
    {
        return $this->version;
    }

    public function setVersion(?Version $version): static
    {
        $this->version = $version;

        return $this;
    }

   

    public function getAttachmentType(): ?AttachmentType
    {
        return $this->attachmentType;
    }

    public function setAttachmentType(?AttachmentType $attachmentType): static
    {
        $this->attachmentType = $attachmentType;

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
}
