<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210510154423 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mission_competence DROP FOREIGN KEY FK_D6D445E415761DAB');
        $this->addSql('DROP TABLE competence');
        $this->addSql('DROP TABLE mission_competence');
        $this->addSql('ALTER TABLE collaborateur CHANGE matricule matricule INT NOT NULL, CHANGE hash hash VARCHAR(255) NOT NULL, CHANGE description description LONGTEXT NOT NULL, CHANGE statut statut VARCHAR(255) NOT NULL, CHANGE slug slug VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE collaborateur RENAME INDEX fk_770cbcd3be6cae90 TO IDX_770CBCD3BE6CAE90');
        $this->addSql('ALTER TABLE comp_etoile ADD def VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE etoile ADD mission_projet VARCHAR(255) NOT NULL, ADD retours VARCHAR(255) DEFAULT NULL, ADD comprehension VARCHAR(255) NOT NULL, ADD atouts VARCHAR(255) DEFAULT NULL, ADD axes VARCHAR(255) DEFAULT NULL, ADD first_actions VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE skill RENAME INDEX fk_5e3de47712469de2 TO IDX_5E3DE47712469DE2');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE competence (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, slug VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE mission_competence (mission_id INT NOT NULL, competence_id INT NOT NULL, INDEX IDX_D6D445E4BE6CAE90 (mission_id), INDEX IDX_D6D445E415761DAB (competence_id), PRIMARY KEY(mission_id, competence_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE mission_competence ADD CONSTRAINT FK_D6D445E415761DAB FOREIGN KEY (competence_id) REFERENCES competence (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mission_competence ADD CONSTRAINT FK_D6D445E4BE6CAE90 FOREIGN KEY (mission_id) REFERENCES mission (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE collaborateur CHANGE matricule matricule INT DEFAULT NULL, CHANGE hash hash VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE description description LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE statut statut VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE slug slug VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE collaborateur RENAME INDEX idx_770cbcd3be6cae90 TO FK_770CBCD3BE6CAE90');
        $this->addSql('ALTER TABLE comp_etoile DROP def');
        $this->addSql('ALTER TABLE etoile DROP mission_projet, DROP retours, DROP comprehension, DROP atouts, DROP axes, DROP first_actions');
        $this->addSql('ALTER TABLE skill RENAME INDEX idx_5e3de47712469de2 TO FK_5E3DE47712469DE2');
    }
}
