<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200924114011 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comp_etoile (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etoile (id INT AUTO_INCREMENT NOT NULL, auteur_id INT DEFAULT NULL, collaborateur_id INT DEFAULT NULL, created_at DATETIME NOT NULL, INDEX IDX_357ADFC360BB6FE6 (auteur_id), INDEX IDX_357ADFC3A848E3B1 (collaborateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mission_cible (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mission_cible_comp_etoile (mission_cible_id INT NOT NULL, comp_etoile_id INT NOT NULL, INDEX IDX_C26A2329A1925FB0 (mission_cible_id), INDEX IDX_C26A2329AD9586CE (comp_etoile_id), PRIMARY KEY(mission_cible_id, comp_etoile_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rating_etoile (id INT AUTO_INCREMENT NOT NULL, competences_id INT DEFAULT NULL, etoile_id INT DEFAULT NULL, note INT NOT NULL, INDEX IDX_1A88B54BA660B158 (competences_id), INDEX IDX_1A88B54B43D8ACEE (etoile_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE etoile ADD CONSTRAINT FK_357ADFC360BB6FE6 FOREIGN KEY (auteur_id) REFERENCES collaborateur (id)');
        $this->addSql('ALTER TABLE etoile ADD CONSTRAINT FK_357ADFC3A848E3B1 FOREIGN KEY (collaborateur_id) REFERENCES collaborateur (id)');
        $this->addSql('ALTER TABLE mission_cible_comp_etoile ADD CONSTRAINT FK_C26A2329A1925FB0 FOREIGN KEY (mission_cible_id) REFERENCES mission_cible (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mission_cible_comp_etoile ADD CONSTRAINT FK_C26A2329AD9586CE FOREIGN KEY (comp_etoile_id) REFERENCES comp_etoile (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE rating_etoile ADD CONSTRAINT FK_1A88B54BA660B158 FOREIGN KEY (competences_id) REFERENCES comp_etoile (id)');
        $this->addSql('ALTER TABLE rating_etoile ADD CONSTRAINT FK_1A88B54B43D8ACEE FOREIGN KEY (etoile_id) REFERENCES etoile (id)');
        $this->addSql('ALTER TABLE collaborateur ADD mission_cible_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE collaborateur ADD CONSTRAINT FK_770CBCD3A1925FB0 FOREIGN KEY (mission_cible_id) REFERENCES mission_cible (id)');
        $this->addSql('CREATE INDEX IDX_770CBCD3A1925FB0 ON collaborateur (mission_cible_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mission_cible_comp_etoile DROP FOREIGN KEY FK_C26A2329AD9586CE');
        $this->addSql('ALTER TABLE rating_etoile DROP FOREIGN KEY FK_1A88B54BA660B158');
        $this->addSql('ALTER TABLE rating_etoile DROP FOREIGN KEY FK_1A88B54B43D8ACEE');
        $this->addSql('ALTER TABLE collaborateur DROP FOREIGN KEY FK_770CBCD3A1925FB0');
        $this->addSql('ALTER TABLE mission_cible_comp_etoile DROP FOREIGN KEY FK_C26A2329A1925FB0');
        $this->addSql('DROP TABLE comp_etoile');
        $this->addSql('DROP TABLE etoile');
        $this->addSql('DROP TABLE mission_cible');
        $this->addSql('DROP TABLE mission_cible_comp_etoile');
        $this->addSql('DROP TABLE rating_etoile');
        $this->addSql('DROP INDEX IDX_770CBCD3A1925FB0 ON collaborateur');
        $this->addSql('ALTER TABLE collaborateur DROP mission_cible_id');
    }
}
