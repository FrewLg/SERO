<?php

namespace App\Entity;

use App\Repository\CouponRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CouponRepository::class)]
class Coupon
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $couponNumber = null;

    #[ORM\ManyToOne(inversedBy: 'coupons')]
    private ?Directorate $directorate = null;

    #[ORM\ManyToOne(inversedBy: 'coupons')]
    private ?Training $training = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $reservedAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?bool $consumed = null;

    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCouponNumber(): ?string
    {
        return $this->couponNumber;
    }

    public function setCouponNumber(?string $couponNumber): static
    {
        $this->couponNumber = $couponNumber;

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

    public function getTraining(): ?Training
    {
        return $this->training;
    }

    public function setTraining(?Training $training): static
    {
        $this->training = $training;

        return $this;
    }

    public function getReservedAt(): ?\DateTimeInterface
    {
        return $this->reservedAt;
    }

    public function setReservedAt(?\DateTimeInterface $reservedAt): static
    {
        $this->reservedAt = $reservedAt;

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

    public function isConsumed(): ?bool
    {
        return $this->consumed;
    }

    public function setConsumed(?bool $consumed): static
    {
        $this->consumed = $consumed;

        return $this;
    }

  
}
