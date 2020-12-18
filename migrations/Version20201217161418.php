<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201217161418 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        //$this->addSql('ALTER TABLE collaborateur DROP FOREIGN KEY FK_770CBCD3A1925FB0');
        //$this->addSql('ALTER TABLE collaborateur CHANGE matricule matricule INT NOT NULL, CHANGE hash hash VARCHAR(255) NOT NULL, CHANGE description description LONGTEXT NOT NULL, CHANGE statut statut VARCHAR(255) NOT NULL, CHANGE slug slug VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE collaborateur ADD CONSTRAINT FK_770CBCD3A1925FB0 FOREIGN KEY (mission_cible_id) REFERENCES mission_cible (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE skill RENAME INDEX fk_5e3de47712469de2 TO IDX_5E3DE47712469DE2');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        //$this->addSql('ALTER TABLE collaborateur DROP FOREIGN KEY FK_770CBCD3A1925FB0');
        //$this->addSql('ALTER TABLE collaborateur CHANGE matricule matricule INT DEFAULT NULL, CHANGE hash hash VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE description description LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE statut statut VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE slug slug VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE collaborateur ADD CONSTRAINT FK_770CBCD3A1925FB0 FOREIGN KEY (mission_cible_id) REFERENCES mission_cible (id)');
        $this->addSql('ALTER TABLE skill RENAME INDEX idx_5e3de47712469de2 TO FK_5E3DE47712469DE2');
    }
}
