<?php

namespace App\Factory;

use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Recipe>
 *
 * @method        Recipe|Proxy                     create(array|callable $attributes = [])
 * @method static Recipe|Proxy                     createOne(array $attributes = [])
 * @method static Recipe|Proxy                     find(object|array|mixed $criteria)
 * @method static Recipe|Proxy                     findOrCreate(array $attributes)
 * @method static Recipe|Proxy                     first(string $sortedField = 'id')
 * @method static Recipe|Proxy                     last(string $sortedField = 'id')
 * @method static Recipe|Proxy                     random(array $attributes = [])
 * @method static Recipe|Proxy                     randomOrCreate(array $attributes = [])
 * @method static RecipeRepository|RepositoryProxy repository()
 * @method static Recipe[]|Proxy[]                 all()
 * @method static Recipe[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Recipe[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static Recipe[]|Proxy[]                 findBy(array $attributes)
 * @method static Recipe[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static Recipe[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class RecipeFactory extends ModelFactory
{
    public const DEFAULT_PICTURE = '';

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
            'description' => self::faker()->text(30),
            'directions' => self::faker()->paragraphs(2, asText: true),
            'expensesPercentage' => self::faker()->randomFloat(2, 1, 99),
            'name' => self::faker()->unique()->words(nb: random_int(1, 5), asText: true),
            'picture' => self::DEFAULT_PICTURE,
            'profitPercentage' => self::faker()->randomFloat(2, 1, 99),
            'published' => self::faker()->boolean(),
            'updatedAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Recipe $recipe): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Recipe::class;
    }
}
