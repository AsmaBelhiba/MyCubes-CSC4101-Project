<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261018120607 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cube_collection ADD COLUMN publiee BOOLEAN DEFAULT 0 NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__cube_collection AS SELECT id, description FROM cube_collection');
        $this->addSql('DROP TABLE cube_collection');
        $this->addSql('CREATE TABLE cube_collection (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, description VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO cube_collection (id, description) SELECT id, description FROM __temp__cube_collection');
        $this->addSql('DROP TABLE __temp__cube_collection');
    }
}
