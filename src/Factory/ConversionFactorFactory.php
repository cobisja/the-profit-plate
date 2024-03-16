<?php

namespace App\Factory;

use App\Entity\ConversionFactor;
use App\Repository\ConversionFactorRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<ConversionFactor>
 *
 * @method        ConversionFactor|Proxy                     create(array|callable $attributes = [])
 * @method static ConversionFactor|Proxy                     createOne(array $attributes = [])
 * @method static ConversionFactor|Proxy                     find(object|array|mixed $criteria)
 * @method static ConversionFactor|Proxy                     findOrCreate(array $attributes)
 * @method static ConversionFactor|Proxy                     first(string $sortedField = 'id')
 * @method static ConversionFactor|Proxy                     last(string $sortedField = 'id')
 * @method static ConversionFactor|Proxy                     random(array $attributes = [])
 * @method static ConversionFactor|Proxy                     randomOrCreate(array $attributes = [])
 * @method static ConversionFactorRepository|RepositoryProxy repository()
 * @method static ConversionFactor[]|Proxy[]                 all()
 * @method static ConversionFactor[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static ConversionFactor[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static ConversionFactor[]|Proxy[]                 findBy(array $attributes)
 * @method static ConversionFactor[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static ConversionFactor[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class ConversionFactorFactory extends ModelFactory
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
            'factor' => self::faker()->randomFloat(
                random_int(1, 4),
                1,
                99.9999
            ),
            'originUnit' => self::faker()->bothify(
                self::faker()->randomElement(['??', '???'])
            ),
            'targetUnit' => self::faker()->bothify(
                self::faker()->randomElement(['??', '???'])
            ),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this// ->afterInstantiate(function(ConversionFactor $conversionFactor): void {})
            ;
    }

    protected static function getClass(): string
    {
        return ConversionFactor::class;
    }
}
