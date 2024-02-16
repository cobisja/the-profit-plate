<?php

namespace App\Entity;

use App\Repository\RecipeTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: RecipeTypeRepository::class)]
#[ORM\Table(name: 'recipe_types')]
class RecipeType
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2, options: ['unsigned' => true, 'default' => 0])]
    private ?string $expensesPercentage = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2, options: ['unsigned' => true, 'default' => 0])]
    private ?string $profitPercentage = null;

    #[ORM\OneToMany(targetEntity: Recipe::class, mappedBy: 'recipeType')]
    private Collection $recipes;

    public function __construct()
    {
        $this->recipes = new ArrayCollection();
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

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getExpensesPercentage(): ?float
    {
        return (float)$this->expensesPercentage;
    }

    public function setExpensesPercentage(string $expensesPercentage): void
    {
        $this->expensesPercentage = $expensesPercentage;
    }

    public function getProfitPercentage(): ?float
    {
        return (float)$this->profitPercentage;
    }

    public function setProfitPercentage(string $profitPercentage): void
    {
        $this->profitPercentage = $profitPercentage;
    }

    /**
     * @return Collection<int, Recipe>
     */
    public function getRecipes(): Collection
    {
        return $this->recipes;
    }

    public function addRecipe(Recipe $recipe): static
    {
        if (!$this->recipes->contains($recipe)) {
            $this->recipes->add($recipe);
            $recipe->setRecipeType($this);
        }

        return $this;
    }

    public function removeRecipe(Recipe $recipe): static
    {
        if ($this->recipes->removeElement($recipe)) {
            // set the owning side to null (unless already changed)
            if ($recipe->getRecipeType() === $this) {
                $recipe->setRecipeType(null);
            }
        }

        return $this;
    }
}
