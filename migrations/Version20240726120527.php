<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240726120527 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE departement ADD capital_town_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE departement ADD CONSTRAINT FK_C1765B63344C5CA0 FOREIGN KEY (capital_town_id) REFERENCES town (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C1765B63344C5CA0 ON departement (capital_town_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE departement DROP FOREIGN KEY FK_C1765B63344C5CA0');
        $this->addSql('DROP INDEX UNIQ_C1765B63344C5CA0 ON departement');
        $this->addSql('ALTER TABLE departement DROP capital_town_id');
    }
}
