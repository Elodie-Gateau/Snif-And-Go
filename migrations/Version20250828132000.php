<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250828132000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trail ADD start_address VARCHAR(255) DEFAULT NULL, ADD start_code INT DEFAULT NULL, ADD start_city VARCHAR(255) DEFAULT NULL, ADD end_address VARCHAR(255) DEFAULT NULL, ADD end_code INT DEFAULT NULL, ADD end_city VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trail DROP start_address, DROP start_code, DROP start_city, DROP end_address, DROP end_code, DROP end_city');
    }
}
