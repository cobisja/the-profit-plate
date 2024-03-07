<?php

declare(strict_types=1);

namespace App\Tests\Controller\Admin\RecipeTypes;

use App\Entity\RecipeType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RecipeTypesIndexControllerTest extends WebTestCase
{
    final public const ADMIN_EMAIL = 'admin@example.org';

    private KernelBrowser $client;
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = self::createClient();
        $this->entityManager = self::getContainer()->get(EntityManagerInterface::class);
        $this->client->loginUser(
            static::getContainer()->get(UserRepository::class)->findOneByEmail(self::ADMIN_EMAIL)
        );
    }

    /**
     * @test
     */
    public function it_should_renders_the_list_of_recipe_types()
    {
        $numberOfRecipeTypes = $this
            ->entityManager
            ->getRepository(RecipeType::class)
            ->count();

        $crawler = $this->client->request('GET', '/admin/recipe_types');
        $this->client->getResponse()->getContent();

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h3.card-title', 'Recipe Types');
        $this->assertSame(1, $crawler->selectLink('New Recipe Type')->count());
        $this->assertSelectorCount($numberOfRecipeTypes, 'tr[data-row-index]');

        /**
         * Test for presence of "Edit" and "Delete" buttons
         * on a non-empty table
         */
        if (0 < $numberOfRecipeTypes) {
            $this->assertSame($numberOfRecipeTypes, $crawler->selectLink('Edit')->count());
            $this->assertSame($numberOfRecipeTypes, $crawler->selectButton('Delete')->count());
        }
    }

}