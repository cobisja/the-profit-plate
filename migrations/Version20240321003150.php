<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240321003150 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE conversion_factors CHANGE factor factor NUMERIC(8, 4) UNSIGNED DEFAULT \'1\' NOT NULL');
        $this->addSql('ALTER TABLE products ADD notes LONGTEXT DEFAULT NULL, CHANGE price_per_unit price_per_unit NUMERIC(8, 2) UNSIGNED DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE recipe_ingredients CHANGE quantity quantity NUMERIC(8, 2) UNSIGNED DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE recipe_types CHANGE expenses_percentage expenses_percentage NUMERIC(5, 2) UNSIGNED DEFAULT \'0\' NOT NULL, CHANGE profit_percentage profit_percentage NUMERIC(5, 2) UNSIGNED DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE recipes CHANGE expenses_percentage expenses_percentage NUMERIC(5, 2) UNSIGNED DEFAULT \'0\' NOT NULL, CHANGE profit_percentage profit_percentage NUMERIC(5, 2) UNSIGNED DEFAULT \'0\' NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE conversion_factors CHANGE factor factor NUMERIC(8, 4) UNSIGNED DEFAULT \'1.0000\' NOT NULL');
        $this->addSql('ALTER TABLE recipes CHANGE expenses_percentage expenses_percentage NUMERIC(5, 2) UNSIGNED DEFAULT \'0.00\' NOT NULL, CHANGE profit_percentage profit_percentage NUMERIC(5, 2) UNSIGNED DEFAULT \'0.00\' NOT NULL');
        $this->addSql('ALTER TABLE products DROP notes, CHANGE price_per_unit price_per_unit NUMERIC(8, 2) UNSIGNED DEFAULT \'0.00\' NOT NULL');
        $this->addSql('ALTER TABLE recipe_types CHANGE expenses_percentage expenses_percentage NUMERIC(5, 2) UNSIGNED DEFAULT \'0.00\' NOT NULL, CHANGE profit_percentage profit_percentage NUMERIC(5, 2) UNSIGNED DEFAULT \'0.00\' NOT NULL');
        $this->addSql('ALTER TABLE recipe_ingredients CHANGE quantity quantity NUMERIC(8, 2) UNSIGNED DEFAULT \'0.00\' NOT NULL');
    }
}
