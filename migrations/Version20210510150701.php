<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210510150701 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comp_etoile ADD def VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE etoile ADD mission_projet VARCHAR(255) NOT NULL, ADD retours VARCHAR(255) DEFAULT NULL, ADD comprehension VARCHAR(255) NOT NULL, ADD atouts VARCHAR(255) DEFAULT NULL, ADD axes VARCHAR(255) DEFAULT NULL, ADD first_actions VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comp_etoile DROP def');
        $this->addSql('ALTER TABLE etoile DROP mission_projet, DROP retours, DROP comprehension, DROP atouts, DROP axes, DROP first_actions');
    }
}
