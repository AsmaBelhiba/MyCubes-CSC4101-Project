<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261018125035 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cube_gallery (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, description VARCHAR(255) NOT NULL, publiee BOOLEAN NOT NULL, member_id INTEGER DEFAULT NULL, CONSTRAINT FK_3ECBD0AE7597D3FE FOREIGN KEY (member_id) REFERENCES member (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_3ECBD0AE7597D3FE ON cube_gallery (member_id)');
        $this->addSql('CREATE TABLE cube_gallery_cube (cube_gallery_id INTEGER NOT NULL, cube_id INTEGER NOT NULL, PRIMARY KEY (cube_gallery_id, cube_id), CONSTRAINT FK_58A73B1138296B43 FOREIGN KEY (cube_gallery_id) REFERENCES cube_gallery (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_58A73B11AD72A4A FOREIGN KEY (cube_id) REFERENCES cube (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_58A73B1138296B43 ON cube_gallery_cube (cube_gallery_id)');
        $this->addSql('CREATE INDEX IDX_58A73B11AD72A4A ON cube_gallery_cube (cube_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__cube_collection AS SELECT id, description FROM cube_collection');
        $this->addSql('DROP TABLE cube_collection');
        $this->addSql('CREATE TABLE cube_collection (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, description VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO cube_collection (id, description) SELECT id, description FROM __temp__cube_collection');
        $this->addSql('DROP TABLE __temp__cube_collection');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE cube_gallery');
        $this->addSql('DROP TABLE cube_gallery_cube');
        $this->addSql('ALTER TABLE cube_collection ADD COLUMN publiee BOOLEAN DEFAULT 0 NOT NULL');
    }
}
