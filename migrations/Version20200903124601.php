<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200903124601 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE evaluation (id INT AUTO_INCREMENT NOT NULL, auteur_id INT NOT NULL, collaborateur_id INT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_1323A57560BB6FE6 (auteur_id), INDEX IDX_1323A575A848E3B1 (collaborateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rating (id INT AUTO_INCREMENT NOT NULL, competences_id INT DEFAULT NULL, evaluation_id INT DEFAULT NULL, note INT NOT NULL, INDEX IDX_D8892622A660B158 (competences_id), INDEX IDX_D8892622456C5646 (evaluation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE evaluation ADD CONSTRAINT FK_1323A57560BB6FE6 FOREIGN KEY (auteur_id) REFERENCES collaborateur (id)');
        $this->addSql('ALTER TABLE evaluation ADD CONSTRAINT FK_1323A575A848E3B1 FOREIGN KEY (collaborateur_id) REFERENCES collaborateur (id)');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D8892622A660B158 FOREIGN KEY (competences_id) REFERENCES skill (id)');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D8892622456C5646 FOREIGN KEY (evaluation_id) REFERENCES evaluation (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY FK_D8892622456C5646');
        $this->addSql('DROP TABLE evaluation');
        $this->addSql('DROP TABLE rating');
    }
}
