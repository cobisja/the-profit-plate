<?php

namespace App\DataFixtures;

use App\Factory\ConversionFactorFactory;
use App\Factory\ProductFactory;
use App\Factory\ProductTypeFactory;
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
        ProductFactory::createMany(15);
    }
}
