<?php

namespace App\Factory;

use App\Entity\RecipeIngredient;
use App\Repository\RecipeIngredientRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<RecipeIngredient>
 *
 * @method        RecipeIngredient|Proxy                     create(array|callable $attributes = [])
 * @method static RecipeIngredient|Proxy                     createOne(array $attributes = [])
 * @method static RecipeIngredient|Proxy                     find(object|array|mixed $criteria)
 * @method static RecipeIngredient|Proxy                     findOrCreate(array $attributes)
 * @method static RecipeIngredient|Proxy                     first(string $sortedField = 'id')
 * @method static RecipeIngredient|Proxy                     last(string $sortedField = 'id')
 * @method static RecipeIngredient|Proxy                     random(array $attributes = [])
 * @method static RecipeIngredient|Proxy                     randomOrCreate(array $attributes = [])
 * @method static RecipeIngredientRepository|RepositoryProxy repository()
 * @method static RecipeIngredient[]|Proxy[]                 all()
 * @method static RecipeIngredient[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static RecipeIngredient[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static RecipeIngredient[]|Proxy[]                 findBy(array $attributes)
 * @method static RecipeIngredient[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static RecipeIngredient[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class RecipeIngredientFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function getDefaults(): array
    {
        return [
            'quantity' => self::faker()->randomFloat(2, 1, 50)
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(RecipeIngredient $recipeIngredient): void {})
        ;
    }

    protected static function getClass(): string
    {
        return RecipeIngredient::class;
    }
}
