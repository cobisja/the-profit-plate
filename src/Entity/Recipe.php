<?php

namespace App\Entity;

use App\Repository\RecipeRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RecipeRepository::class)]
#[ORM\Table(name: 'recipes')]
class Recipe
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
    #[Assert\NotBlank]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $picture = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank]
    private ?string $directions = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2, options: ['unsigned' => true, 'default' => 0])]
    #[Assert\NotBlank]
    #[Assert\Positive]
    private ?string $expensesPercentage = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2, options: ['unsigned' => true, 'default' => 0])]
    #[Assert\NotBlank]
    #[Assert\Positive]
    private ?string $profitPercentage = null;

    #[ORM\Column(type: Types::INTEGER, options: ['unsigned' => true, 'default' => 1])]
    #[Assert\Positive]
    private int $numberOfServings = 1;

    #[ORM\Column]
    private ?DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'recipes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?RecipeType $recipeType = null;

    #[ORM\OneToMany(
        targetEntity: RecipeIngredient::class,
        mappedBy: 'recipe',
        orphanRemoval: true,
    )]
    private Collection $ingredients;

    #[ORM\Column(options: ['default' => false])]
    private ?bool $published = false;

    public function __construct()
    {
        $this->ingredients = new ArrayCollection();
    }

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

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): void
    {
        $this->picture = $picture;
    }

    public function getDirections(): ?string
    {
        return $this->directions;
    }

    public function setDirections(?string $directions): void
    {
        $this->directions = $directions;
    }

    public function getExpensesPercentage(): ?string
    {
        return $this->expensesPercentage;
    }

    public function setExpensesPercentage(?string $expensesPercentage): void
    {
        $this->expensesPercentage = $expensesPercentage;
    }

    public function getProfitPercentage(): ?string
    {
        return $this->profitPercentage;
    }

    public function setProfitPercentage(?string $profitPercentage): void
    {
        $this->profitPercentage = $profitPercentage;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getRecipeType(): ?RecipeType
    {
        return $this->recipeType;
    }

    public function setRecipeType(?RecipeType $recipeType): void
    {
        $this->recipeType = $recipeType;
    }

    /**
     * @return Collection<int, RecipeIngredient>
     */
    public function getIngredients(): Collection
    {
        return $this->ingredients;
    }

    public function addIngredient(RecipeIngredient $ingredient): void
    {
        if (!$this->ingredients->contains($ingredient)) {
            $this->ingredients->add($ingredient);
            $ingredient->setRecipe($this);
        }
    }

    public function removeIngredient(RecipeIngredient $ingredient): void
    {
        if ($this->ingredients->removeElement($ingredient)) {
            if ($ingredient->getRecipe() === $this) {
                $ingredient->setRecipe(null);
            }
        }
    }

    public function isPublished(): ?bool
    {
        return $this->published;
    }

    public function setPublished(bool $published): void
    {
        $this->published = $published;
    }

    public function updateUpdatedAt(): void
    {
        $this->updatedAt = new DateTimeImmutable();
    }

    public function getNumberOfServings(): int
    {
        return $this->numberOfServings;
    }

    public function setNumberOfServings(int $numberOfServings): void
    {
        $this->numberOfServings = $numberOfServings;
    }

    public function getIngredientCosts(): float
    {
        return array_sum(
            array_map(
                static fn(RecipeIngredient $ingredient) => $ingredient->getCost(),
                $this->ingredients->toArray()
            )
        );
    }
}
