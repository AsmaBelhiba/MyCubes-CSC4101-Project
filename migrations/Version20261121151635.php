<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261121151635 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE member_favorite_cube (member_id INTEGER NOT NULL, cube_id INTEGER NOT NULL, PRIMARY KEY (member_id, cube_id), CONSTRAINT FK_3DEE100B7597D3FE FOREIGN KEY (member_id) REFERENCES member (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_3DEE100BAD72A4A FOREIGN KEY (cube_id) REFERENCES cube (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_3DEE100B7597D3FE ON member_favorite_cube (member_id)');
        $this->addSql('CREATE INDEX IDX_3DEE100BAD72A4A ON member_favorite_cube (cube_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE member_favorite_cube');
    }
}
