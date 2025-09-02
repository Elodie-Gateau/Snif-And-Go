<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250902103112 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trail CHANGE name_search name_search VARCHAR(255) DEFAULT NULL, CHANGE start_city_search start_city_search VARCHAR(255) DEFAULT NULL, CHANGE end_city_search end_city_search VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trail CHANGE name_search name_search VARCHAR(255) NOT NULL, CHANGE start_city_search start_city_search VARCHAR(255) NOT NULL, CHANGE end_city_search end_city_search VARCHAR(255) NOT NULL');
    }
}
