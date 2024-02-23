<?php

namespace App\Factory;

use App\Entity\RecipeType;
use App\Repository\RecipeTypeRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<RecipeType>
 *
 * @method        RecipeType|Proxy                     create(array|callable $attributes = [])
 * @method static RecipeType|Proxy                     createOne(array $attributes = [])
 * @method static RecipeType|Proxy                     find(object|array|mixed $criteria)
 * @method static RecipeType|Proxy                     findOrCreate(array $attributes)
 * @method static RecipeType|Proxy                     first(string $sortedField = 'id')
 * @method static RecipeType|Proxy                     last(string $sortedField = 'id')
 * @method static RecipeType|Proxy                     random(array $attributes = [])
 * @method static RecipeType|Proxy                     randomOrCreate(array $attributes = [])
 * @method static RecipeTypeRepository|RepositoryProxy repository()
 * @method static RecipeType[]|Proxy[]                 all()
 * @method static RecipeType[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static RecipeType[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static RecipeType[]|Proxy[]                 findBy(array $attributes)
 * @method static RecipeType[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static RecipeType[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class RecipeTypeFactory extends ModelFactory
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
            'expensesPercentage' => self::faker()->randomFloat(2, 10, 50),
            'name' => self::faker()->word(),
            'profitPercentage' => self::faker()->randomFloat(2, 10, 50),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this// ->afterInstantiate(function(RecipeType $recipeType): void {})
            ;
    }

    protected static function getClass(): string
    {
        return RecipeType::class;
    }
}
