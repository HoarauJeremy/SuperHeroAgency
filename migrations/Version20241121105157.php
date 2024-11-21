<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241121105157 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE super_hero_super_pouvoir (super_hero_id INT NOT NULL, super_pouvoir_id INT NOT NULL, INDEX IDX_977CCA11B62BE361 (super_hero_id), INDEX IDX_977CCA11DE67A996 (super_pouvoir_id), PRIMARY KEY(super_hero_id, super_pouvoir_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE super_hero_super_pouvoir ADD CONSTRAINT FK_977CCA11B62BE361 FOREIGN KEY (super_hero_id) REFERENCES super_hero (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE super_hero_super_pouvoir ADD CONSTRAINT FK_977CCA11DE67A996 FOREIGN KEY (super_pouvoir_id) REFERENCES super_pouvoir (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE super_hero_super_pouvoir DROP FOREIGN KEY FK_977CCA11B62BE361');
        $this->addSql('ALTER TABLE super_hero_super_pouvoir DROP FOREIGN KEY FK_977CCA11DE67A996');
        $this->addSql('DROP TABLE super_hero_super_pouvoir');
    }
}
