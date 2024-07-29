<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240729135820 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_town (user_id INT NOT NULL, town_id INT NOT NULL, INDEX IDX_36678B6DA76ED395 (user_id), INDEX IDX_36678B6D75E23604 (town_id), PRIMARY KEY(user_id, town_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_town ADD CONSTRAINT FK_36678B6DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_town ADD CONSTRAINT FK_36678B6D75E23604 FOREIGN KEY (town_id) REFERENCES town (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_town DROP FOREIGN KEY FK_36678B6DA76ED395');
        $this->addSql('ALTER TABLE user_town DROP FOREIGN KEY FK_36678B6D75E23604');
        $this->addSql('DROP TABLE user_town');
    }
}
