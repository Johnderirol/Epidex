<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200916140851 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE pdi (id INT AUTO_INCREMENT NOT NULL, auteur_id INT DEFAULT NULL, created_at DATETIME NOT NULL, situation_apprenante VARCHAR(255) NOT NULL, progres VARCHAR(255) NOT NULL, contributeurs_roles VARCHAR(255) NOT NULL, end_date DATE NOT NULL, INDEX IDX_9E4FC61D60BB6FE6 (auteur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pdi ADD CONSTRAINT FK_9E4FC61D60BB6FE6 FOREIGN KEY (auteur_id) REFERENCES collaborateur (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE pdi');
    }
}
