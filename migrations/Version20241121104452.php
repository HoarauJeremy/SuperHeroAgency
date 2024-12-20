<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241121104452 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE equipe (id INT AUTO_INCREMENT NOT NULL, chef_id INT NOT NULL, mission_en_cours_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, est_actif TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_2449BA15150A48F1 (chef_id), UNIQUE INDEX UNIQ_2449BA1554B3DCCA (mission_en_cours_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE equipe_super_hero (equipe_id INT NOT NULL, super_hero_id INT NOT NULL, INDEX IDX_970E3D1E6D861B89 (equipe_id), INDEX IDX_970E3D1EB62BE361 (super_hero_id), PRIMARY KEY(equipe_id, super_hero_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mission (id INT AUTO_INCREMENT NOT NULL, equipe_en_charge_id INT DEFAULT NULL, titre VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, statut VARCHAR(255) NOT NULL, debut DATETIME NOT NULL, fin DATETIME NOT NULL, localisation VARCHAR(255) NOT NULL, niveau_danger INT NOT NULL, INDEX IDX_9067F23CB4A1A213 (equipe_en_charge_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE super_hero (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, alter_ego VARCHAR(255) DEFAULT NULL, est_disponible TINYINT(1) NOT NULL, niveau_energie INT NOT NULL, biographie LONGTEXT DEFAULT NULL, nom_image VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE super_pouvoir (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, niveau INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE equipe ADD CONSTRAINT FK_2449BA15150A48F1 FOREIGN KEY (chef_id) REFERENCES super_hero (id)');
        $this->addSql('ALTER TABLE equipe ADD CONSTRAINT FK_2449BA1554B3DCCA FOREIGN KEY (mission_en_cours_id) REFERENCES mission (id)');
        $this->addSql('ALTER TABLE equipe_super_hero ADD CONSTRAINT FK_970E3D1E6D861B89 FOREIGN KEY (equipe_id) REFERENCES equipe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE equipe_super_hero ADD CONSTRAINT FK_970E3D1EB62BE361 FOREIGN KEY (super_hero_id) REFERENCES super_hero (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mission ADD CONSTRAINT FK_9067F23CB4A1A213 FOREIGN KEY (equipe_en_charge_id) REFERENCES equipe (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE equipe DROP FOREIGN KEY FK_2449BA15150A48F1');
        $this->addSql('ALTER TABLE equipe DROP FOREIGN KEY FK_2449BA1554B3DCCA');
        $this->addSql('ALTER TABLE equipe_super_hero DROP FOREIGN KEY FK_970E3D1E6D861B89');
        $this->addSql('ALTER TABLE equipe_super_hero DROP FOREIGN KEY FK_970E3D1EB62BE361');
        $this->addSql('ALTER TABLE mission DROP FOREIGN KEY FK_9067F23CB4A1A213');
        $this->addSql('DROP TABLE equipe');
        $this->addSql('DROP TABLE equipe_super_hero');
        $this->addSql('DROP TABLE mission');
        $this->addSql('DROP TABLE super_hero');
        $this->addSql('DROP TABLE super_pouvoir');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
