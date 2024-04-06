<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240406023047 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recipe_ingredients DROP FOREIGN KEY FK_9F925F2B4584665A');
        $this->addSql('ALTER TABLE recipe_ingredients CHANGE product_id product_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', CHANGE quantity quantity NUMERIC(8, 2) UNSIGNED DEFAULT \'0\' NOT NULL, CHANGE cost cost NUMERIC(8, 2) UNSIGNED DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE recipe_ingredients ADD CONSTRAINT FK_9F925F2B4584665A FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recipe_ingredients DROP FOREIGN KEY FK_9F925F2B4584665A');
        $this->addSql('ALTER TABLE recipe_ingredients CHANGE product_id product_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', CHANGE quantity quantity NUMERIC(8, 2) UNSIGNED DEFAULT \'0.00\' NOT NULL, CHANGE cost cost NUMERIC(8, 2) UNSIGNED DEFAULT \'0.00\' NOT NULL');
        $this->addSql('ALTER TABLE recipe_ingredients ADD CONSTRAINT FK_9F925F2B4584665A FOREIGN KEY (product_id) REFERENCES products (id) ON UPDATE NO ACTION ON DELETE SET NULL');
    }
}
