<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240216155454 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE recipe_ingredients (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', recipe_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', product_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', quantity NUMERIC(8, 2) NOT NULL, unit VARCHAR(6) NOT NULL, INDEX IDX_9F925F2B59D8A214 (recipe_id), INDEX IDX_9F925F2B4584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE recipe_ingredients ADD CONSTRAINT FK_9F925F2B59D8A214 FOREIGN KEY (recipe_id) REFERENCES recipes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recipe_ingredients ADD CONSTRAINT FK_9F925F2B4584665A FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE products ADD product_type_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT FK_B3BA5A5A14959723 FOREIGN KEY (product_type_id) REFERENCES product_types (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B3BA5A5A5E237E06 ON products (name)');
        $this->addSql('CREATE INDEX IDX_B3BA5A5A14959723 ON products (product_type_id)');
        $this->addSql('ALTER TABLE recipes ADD recipe_type_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE recipes ADD CONSTRAINT FK_A369E2B589A882D3 FOREIGN KEY (recipe_type_id) REFERENCES recipe_types (id)');
        $this->addSql('CREATE INDEX IDX_A369E2B589A882D3 ON recipes (recipe_type_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recipe_ingredients DROP FOREIGN KEY FK_9F925F2B59D8A214');
        $this->addSql('ALTER TABLE recipe_ingredients DROP FOREIGN KEY FK_9F925F2B4584665A');
        $this->addSql('DROP TABLE recipe_ingredients');
        $this->addSql('ALTER TABLE recipes DROP FOREIGN KEY FK_A369E2B589A882D3');
        $this->addSql('DROP INDEX IDX_A369E2B589A882D3 ON recipes');
        $this->addSql('ALTER TABLE recipes DROP recipe_type_id');
        $this->addSql('ALTER TABLE products DROP FOREIGN KEY FK_B3BA5A5A14959723');
        $this->addSql('DROP INDEX UNIQ_B3BA5A5A5E237E06 ON products');
        $this->addSql('DROP INDEX IDX_B3BA5A5A14959723 ON products');
        $this->addSql('ALTER TABLE products DROP product_type_id');
    }
}
