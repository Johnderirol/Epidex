<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200916144331 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pdi ADD collaborateur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pdi ADD CONSTRAINT FK_9E4FC61DA848E3B1 FOREIGN KEY (collaborateur_id) REFERENCES collaborateur (id)');
        $this->addSql('CREATE INDEX IDX_9E4FC61DA848E3B1 ON pdi (collaborateur_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pdi DROP FOREIGN KEY FK_9E4FC61DA848E3B1');
        $this->addSql('DROP INDEX IDX_9E4FC61DA848E3B1 ON pdi');
        $this->addSql('ALTER TABLE pdi DROP collaborateur_id');
    }
}
