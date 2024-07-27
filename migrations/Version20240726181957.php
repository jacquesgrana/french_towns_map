<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240726181957 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE town ADD departement_id INT NOT NULL');
        $this->addSql('ALTER TABLE town ADD CONSTRAINT FK_4CE6C7A4CCF9E01E FOREIGN KEY (departement_id) REFERENCES departement (id)');
        $this->addSql('CREATE INDEX IDX_4CE6C7A4CCF9E01E ON town (departement_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE town DROP FOREIGN KEY FK_4CE6C7A4CCF9E01E');
        $this->addSql('DROP INDEX IDX_4CE6C7A4CCF9E01E ON town');
        $this->addSql('ALTER TABLE town DROP departement_id');
    }
}
