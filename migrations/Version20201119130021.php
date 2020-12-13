<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201119130021 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rating_etoile ADD collaborateur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE rating_etoile ADD CONSTRAINT FK_1A88B54BA848E3B1 FOREIGN KEY (collaborateur_id) REFERENCES collaborateur (id)');
        $this->addSql('CREATE INDEX IDX_1A88B54BA848E3B1 ON rating_etoile (collaborateur_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rating_etoile DROP FOREIGN KEY FK_1A88B54BA848E3B1');
        $this->addSql('DROP INDEX IDX_1A88B54BA848E3B1 ON rating_etoile');
        $this->addSql('ALTER TABLE rating_etoile DROP collaborateur_id');
    }
}
