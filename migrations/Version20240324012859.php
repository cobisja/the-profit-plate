<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240324012859 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE product_price_variation (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', product_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', old_price NUMERIC(8, 2) UNSIGNED NOT NULL, new_price NUMERIC(8, 2) UNSIGNED NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_D3A1058B4584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product_price_variation ADD CONSTRAINT FK_D3A1058B4584665A FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE conversion_factors CHANGE factor factor NUMERIC(8, 4) UNSIGNED DEFAULT \'1\' NOT NULL');
        $this->addSql('ALTER TABLE products CHANGE price_per_unit price_per_unit NUMERIC(8, 2) UNSIGNED DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE recipe_ingredients CHANGE quantity quantity NUMERIC(8, 2) UNSIGNED DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE recipe_types CHANGE expenses_percentage expenses_percentage NUMERIC(5, 2) UNSIGNED DEFAULT \'0\' NOT NULL, CHANGE profit_percentage profit_percentage NUMERIC(5, 2) UNSIGNED DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE recipes CHANGE expenses_percentage expenses_percentage NUMERIC(5, 2) UNSIGNED DEFAULT \'0\' NOT NULL, CHANGE profit_percentage profit_percentage NUMERIC(5, 2) UNSIGNED DEFAULT \'0\' NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_price_variation DROP FOREIGN KEY FK_D3A1058B4584665A');
        $this->addSql('DROP TABLE product_price_variation');
        $this->addSql('ALTER TABLE recipes CHANGE expenses_percentage expenses_percentage NUMERIC(5, 2) UNSIGNED DEFAULT \'0.00\' NOT NULL, CHANGE profit_percentage profit_percentage NUMERIC(5, 2) UNSIGNED DEFAULT \'0.00\' NOT NULL');
        $this->addSql('ALTER TABLE recipe_types CHANGE expenses_percentage expenses_percentage NUMERIC(5, 2) UNSIGNED DEFAULT \'0.00\' NOT NULL, CHANGE profit_percentage profit_percentage NUMERIC(5, 2) UNSIGNED DEFAULT \'0.00\' NOT NULL');
        $this->addSql('ALTER TABLE conversion_factors CHANGE factor factor NUMERIC(8, 4) UNSIGNED DEFAULT \'1.0000\' NOT NULL');
        $this->addSql('ALTER TABLE products CHANGE price_per_unit price_per_unit NUMERIC(8, 2) UNSIGNED DEFAULT \'0.00\' NOT NULL');
        $this->addSql('ALTER TABLE recipe_ingredients CHANGE quantity quantity NUMERIC(8, 2) UNSIGNED DEFAULT \'0.00\' NOT NULL');
    }
}
