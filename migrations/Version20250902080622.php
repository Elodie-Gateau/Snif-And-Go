<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250902080622 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE dog_breed (id INT AUTO_INCREMENT NOT NULL, name_fr VARCHAR(255) NOT NULL, name_en VARCHAR(255) NOT NULL, size VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE dog ADD dog_breed_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE dog ADD CONSTRAINT FK_812C397DC0EB1E2E FOREIGN KEY (dog_breed_id) REFERENCES dog_breed (id)');
        $this->addSql('CREATE INDEX IDX_812C397DC0EB1E2E ON dog (dog_breed_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dog DROP FOREIGN KEY FK_812C397DC0EB1E2E');
        $this->addSql('DROP TABLE dog_breed');
        $this->addSql('DROP INDEX IDX_812C397DC0EB1E2E ON dog');
        $this->addSql('ALTER TABLE dog DROP dog_breed_id');
    }
}
