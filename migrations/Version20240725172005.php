<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240725172005 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE town (id INT AUTO_INCREMENT NOT NULL, position_gps_id INT NOT NULL, town_code VARCHAR(10) NOT NULL, town_name VARCHAR(120) NOT NULL, town_zip_code VARCHAR(20) NOT NULL, INDEX IDX_4CE6C7A445850353 (position_gps_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE town ADD CONSTRAINT FK_4CE6C7A445850353 FOREIGN KEY (position_gps_id) REFERENCES position_gps (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE town DROP FOREIGN KEY FK_4CE6C7A445850353');
        $this->addSql('DROP TABLE town');
    }
}
