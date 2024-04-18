<?php

namespace App\DataFixtures;

use App\Entity\ConversionFactor;
use App\Factory\ConversionFactorFactory;
use App\Factory\ProductFactory;
use App\Factory\ProductTypeFactory;
use App\Factory\RecipeFactory;
use App\Factory\RecipeIngredientFactory;
use App\Factory\RecipeTypeFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        UserFactory::createOne();
        RecipeTypeFactory::createMany(5);
        ProductTypeFactory::createMany(5);
        ConversionFactorFactory::createMany(10);
        ProductFactory::createMany(15, static fn() => ['productType' => ProductTypeFactory::random()]);
        RecipeFactory::createMany(20, static fn() => ['recipeType' => RecipeTypeFactory::random()]);

        $unitsForRecipeIngredients = $this->getUnitForRecipeIngredient($manager);

        RecipeIngredientFactory::createSequence(function () use ($unitsForRecipeIngredients) {
            foreach (range(1, 50) as $i) {
                shuffle($unitsForRecipeIngredients);

                yield [
                    'recipe' => RecipeFactory::random(),
                    'product' => ProductFactory::random(),
                    'unit' => $unitsForRecipeIngredients[0]
                ];
            }
        });
    }

    /**
     * @return string[]
     */
    private function getUnitForRecipeIngredient(ObjectManager $manager): array
    {
        $conversionFactors = $manager->getRepository(ConversionFactor::class)->findAll();

        $originUnits = array_map(
            static fn(ConversionFactor $conversionFactor) => $conversionFactor->getOriginUnit(),
            $conversionFactors
        );

        $targetUnits = array_map(
            static fn(ConversionFactor $conversionFactor) => $conversionFactor->getTargetUnit(),
            $conversionFactors
        );

        return array_merge($originUnits, $targetUnits);
    }
}
