<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241128115743 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mission DROP FOREIGN KEY FK_9067F23CB4A1A213');
        $this->addSql('ALTER TABLE equipe_super_hero DROP FOREIGN KEY FK_970E3D1E6D861B89');
        $this->addSql('ALTER TABLE equipe_super_hero DROP FOREIGN KEY FK_970E3D1EB62BE361');
        $this->addSql('ALTER TABLE equipe DROP FOREIGN KEY FK_2449BA15150A48F1');
        $this->addSql('ALTER TABLE equipe DROP FOREIGN KEY FK_2449BA1554B3DCCA');
        $this->addSql('DROP TABLE equipe_super_hero');
        $this->addSql('DROP TABLE equipe');
        $this->addSql('DROP INDEX IDX_9067F23CB4A1A213 ON mission');
        $this->addSql('ALTER TABLE mission DROP equipe_en_charge_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE equipe_super_hero (equipe_id INT NOT NULL, super_hero_id INT NOT NULL, INDEX IDX_970E3D1E6D861B89 (equipe_id), INDEX IDX_970E3D1EB62BE361 (super_hero_id), PRIMARY KEY(equipe_id, super_hero_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE equipe (id INT AUTO_INCREMENT NOT NULL, chef_id INT NOT NULL, mission_en_cours_id INT DEFAULT NULL, nom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, est_actif TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_2449BA1554B3DCCA (mission_en_cours_id), INDEX IDX_2449BA15150A48F1 (chef_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE equipe_super_hero ADD CONSTRAINT FK_970E3D1E6D861B89 FOREIGN KEY (equipe_id) REFERENCES equipe (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE equipe_super_hero ADD CONSTRAINT FK_970E3D1EB62BE361 FOREIGN KEY (super_hero_id) REFERENCES super_hero (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE equipe ADD CONSTRAINT FK_2449BA15150A48F1 FOREIGN KEY (chef_id) REFERENCES super_hero (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE equipe ADD CONSTRAINT FK_2449BA1554B3DCCA FOREIGN KEY (mission_en_cours_id) REFERENCES mission (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE mission ADD equipe_en_charge_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE mission ADD CONSTRAINT FK_9067F23CB4A1A213 FOREIGN KEY (equipe_en_charge_id) REFERENCES equipe (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_9067F23CB4A1A213 ON mission (equipe_en_charge_id)');
    }
}
