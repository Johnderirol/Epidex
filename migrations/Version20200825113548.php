<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200825113548 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE secteur ADD responsable_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE secteur ADD CONSTRAINT FK_8045251F53C59D72 FOREIGN KEY (responsable_id) REFERENCES collaborateur (id)');
        $this->addSql('CREATE INDEX IDX_8045251F53C59D72 ON secteur (responsable_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE secteur DROP FOREIGN KEY FK_8045251F53C59D72');
        $this->addSql('DROP INDEX IDX_8045251F53C59D72 ON secteur');
        $this->addSql('ALTER TABLE secteur DROP responsable_id');
    }
}
