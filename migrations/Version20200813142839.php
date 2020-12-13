<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200813142839 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE collaborateur ADD rayon_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE collaborateur ADD CONSTRAINT FK_770CBCD3D3202E52 FOREIGN KEY (rayon_id) REFERENCES rayon (id)');
        $this->addSql('CREATE INDEX IDX_770CBCD3D3202E52 ON collaborateur (rayon_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE collaborateur DROP FOREIGN KEY FK_770CBCD3D3202E52');
        $this->addSql('DROP INDEX IDX_770CBCD3D3202E52 ON collaborateur');
        $this->addSql('ALTER TABLE collaborateur DROP rayon_id');
    }
}
