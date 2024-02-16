<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240216160530 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE conversion_factors CHANGE factor factor NUMERIC(8, 4) UNSIGNED DEFAULT \'1\' NOT NULL');
        $this->addSql('ALTER TABLE products CHANGE price_per_unit price_per_unit NUMERIC(8, 2) UNSIGNED DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE recipe_ingredients CHANGE quantity quantity NUMERIC(8, 2) UNSIGNED DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE recipe_types CHANGE expenses_percentage expenses_percentage NUMERIC(5, 2) UNSIGNED DEFAULT \'0\' NOT NULL, CHANGE profit_percentage profit_percentage NUMERIC(5, 2) UNSIGNED DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE recipes ADD published TINYINT(1) DEFAULT 0 NOT NULL, CHANGE expenses_percentage expenses_percentage NUMERIC(5, 2) UNSIGNED DEFAULT \'0\' NOT NULL, CHANGE profit_percentage profit_percentage NUMERIC(5, 2) UNSIGNED DEFAULT \'0\' NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recipes DROP published, CHANGE expenses_percentage expenses_percentage NUMERIC(5, 2) NOT NULL, CHANGE profit_percentage profit_percentage NUMERIC(5, 2) NOT NULL');
        $this->addSql('ALTER TABLE recipe_types CHANGE expenses_percentage expenses_percentage NUMERIC(5, 2) NOT NULL, CHANGE profit_percentage profit_percentage NUMERIC(5, 2) NOT NULL');
        $this->addSql('ALTER TABLE products CHANGE price_per_unit price_per_unit NUMERIC(8, 2) NOT NULL');
        $this->addSql('ALTER TABLE conversion_factors CHANGE factor factor NUMERIC(8, 4) NOT NULL');
        $this->addSql('ALTER TABLE recipe_ingredients CHANGE quantity quantity NUMERIC(8, 2) NOT NULL');
    }
}
