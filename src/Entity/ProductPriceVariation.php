<?php

namespace App\Entity;

use App\Repository\ProductPriceVariationRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: ProductPriceVariationRepository::class)]
class ProductPriceVariation
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 8, scale: 2, options: ['unsigned' => true])]
    private ?string $oldPrice = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 8, scale: 2, options: ['unsigned' => true])]
    private ?string $newPrice = null;

    #[ORM\Column]
    private ?DateTimeImmutable $createdAt;

    #[ORM\ManyToOne(inversedBy: 'priceVariations')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Product $product = null;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getOldPrice(): ?string
    {
        return $this->oldPrice;
    }

    public function setOldPrice(string $oldPrice): static
    {
        $this->oldPrice = $oldPrice;

        return $this;
    }

    public function getNewPrice(): ?string
    {
        return $this->newPrice;
    }

    public function setNewPrice(string $newPrice): static
    {
        $this->newPrice = $newPrice;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): static
    {
        $this->product = $product;

        return $this;
    }
}
