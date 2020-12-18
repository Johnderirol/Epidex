<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201218115855 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        //$this->addSql('ALTER TABLE collaborateur DROP FOREIGN KEY FK_770CBCD3BE6CAE90');
        //$this->addSql('ALTER TABLE collaborateur CHANGE matricule matricule INT NOT NULL, CHANGE hash hash VARCHAR(255) NOT NULL, CHANGE description description LONGTEXT NOT NULL, CHANGE statut statut VARCHAR(255) NOT NULL, CHANGE slug slug VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE collaborateur ADD CONSTRAINT FK_770CBCD3BE6CAE90 FOREIGN KEY (mission_id) REFERENCES mission (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE collaborateur RENAME INDEX fk_770cbcd3a1925fb0 TO IDX_770CBCD3A1925FB0');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        //$this->addSql('ALTER TABLE collaborateur DROP FOREIGN KEY FK_770CBCD3BE6CAE90');
        //$this->addSql('ALTER TABLE collaborateur CHANGE matricule matricule INT DEFAULT NULL, CHANGE hash hash VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE description description LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE statut statut VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE slug slug VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE collaborateur ADD CONSTRAINT FK_770CBCD3BE6CAE90 FOREIGN KEY (mission_id) REFERENCES mission (id)');
        $this->addSql('ALTER TABLE collaborateur RENAME INDEX idx_770cbcd3a1925fb0 TO FK_770CBCD3A1925FB0');
    }
}
