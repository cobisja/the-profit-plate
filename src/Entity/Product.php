<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ORM\Table(name: 'products')]
class Product
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $picture = null;

    #[ORM\Column(length: 6)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 6)]
    private ?string $unit = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 8, scale: 2, options: ['unsigned' => true, 'default' => 0])]
    #[Assert\NotBlank]
    #[Assert\Positive]
    private ?string $pricePerUnit = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: 2, options: ['unsigned' => true, 'default' => 0])]
    #[Assert\NotBlank]
    #[Assert\PositiveOrZero]
    private ?string $wasteRate = null;

    #[ORM\Column]
    private ?DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?ProductType $productType = null;

    #[ORM\OneToMany(RecipeIngredient::class, mappedBy: 'product')]
    private Collection $recipeIngredients;

    public function __construct()
    {
        $this->updateUpdatedAt();

        $this->priceVariations = new ArrayCollection();
        $this->recipeIngredients = new ArrayCollection();
    }

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $notes = null;

    #[ORM\OneToMany(targetEntity: ProductPriceVariation::class, mappedBy: 'product')]
    #[ORM\OrderBy(['createdAt' => 'DESC'])]
    private Collection $priceVariations;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function setId(Uuid $id): void
    {
        $this->id = $id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = strtolower($name);
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): void
    {
        $this->picture = $picture;
    }

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function setUnit(string $unit): void
    {
        $this->unit = strtolower($unit);
    }

    public function getPricePerUnit(): ?string
    {
        return $this->pricePerUnit;
    }

    public function setPricePerUnit(string $pricePerUnit): void
    {
        $this->pricePerUnit = $pricePerUnit;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getProductType(): ?ProductType
    {
        return $this->productType;
    }

    public function setProductType(?ProductType $productType): void
    {
        $this->productType = $productType;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): static
    {
        $this->notes = $notes;

        return $this;
    }

    public function updateUpdatedAt(?DateTimeImmutable $updatedAt = null): void
    {
        $this->updatedAt = $updatedAt ?? new DateTimeImmutable();
    }

    /**
     * @return Collection<int, ProductPriceVariation>
     */
    public function getPriceVariations(): Collection
    {
        return $this->priceVariations;
    }

    public function addPriceVariation(ProductPriceVariation $priceVariation): static
    {
        if (!$this->priceVariations->contains($priceVariation)) {
            $this->priceVariations->add($priceVariation);
            $priceVariation->setProduct($this);
        }

        return $this;
    }

    public function removePriceVariation(ProductPriceVariation $priceVariation): static
    {
        if ($this->priceVariations->removeElement($priceVariation)) {
            // set the owning side to null (unless already changed)
            if ($priceVariation->getProduct() === $this) {
                $priceVariation->setProduct(null);
            }
        }

        return $this;
    }

    public function getWasteRate(): ?string
    {
        return $this->wasteRate;
    }

    public function setWasteRate(?string $wasteRate): void
    {
        $this->wasteRate = $wasteRate;
    }

    public function netCost(): float
    {
        $pricePerUnit = (float)$this->pricePerUnit;

        return round($pricePerUnit + $pricePerUnit * $this->wasteRate / 100, 2);
    }

    public function getRecipeIngredients(): Collection
    {
        return $this->recipeIngredients;
    }

    public function setRecipeIngredients(Collection $recipeIngredients): void
    {
        $this->recipeIngredients = $recipeIngredients;
    }
}
