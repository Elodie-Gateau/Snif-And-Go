<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250826130859 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dog ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE dog ADD CONSTRAINT FK_812C397DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_812C397DA76ED395 ON dog (user_id)');
        $this->addSql('ALTER TABLE trail ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE trail ADD CONSTRAINT FK_B268858FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_B268858FA76ED395 ON trail (user_id)');
        $this->addSql('ALTER TABLE walk ADD trail_id INT NOT NULL');
        $this->addSql('ALTER TABLE walk ADD CONSTRAINT FK_8D917A5589B51C5B FOREIGN KEY (trail_id) REFERENCES trail (id)');
        $this->addSql('CREATE INDEX IDX_8D917A5589B51C5B ON walk (trail_id)');
        $this->addSql('ALTER TABLE walk_registration ADD dog_id INT NOT NULL, ADD walk_id INT NOT NULL');
        $this->addSql('ALTER TABLE walk_registration ADD CONSTRAINT FK_E94984DC634DFEB FOREIGN KEY (dog_id) REFERENCES dog (id)');
        $this->addSql('ALTER TABLE walk_registration ADD CONSTRAINT FK_E94984DC5EEE1B48 FOREIGN KEY (walk_id) REFERENCES walk (id)');
        $this->addSql('CREATE INDEX IDX_E94984DC634DFEB ON walk_registration (dog_id)');
        $this->addSql('CREATE INDEX IDX_E94984DC5EEE1B48 ON walk_registration (walk_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dog DROP FOREIGN KEY FK_812C397DA76ED395');
        $this->addSql('DROP INDEX IDX_812C397DA76ED395 ON dog');
        $this->addSql('ALTER TABLE dog DROP user_id');
        $this->addSql('ALTER TABLE trail DROP FOREIGN KEY FK_B268858FA76ED395');
        $this->addSql('DROP INDEX IDX_B268858FA76ED395 ON trail');
        $this->addSql('ALTER TABLE trail DROP user_id');
        $this->addSql('ALTER TABLE walk DROP FOREIGN KEY FK_8D917A5589B51C5B');
        $this->addSql('DROP INDEX IDX_8D917A5589B51C5B ON walk');
        $this->addSql('ALTER TABLE walk DROP trail_id');
        $this->addSql('ALTER TABLE walk_registration DROP FOREIGN KEY FK_E94984DC634DFEB');
        $this->addSql('ALTER TABLE walk_registration DROP FOREIGN KEY FK_E94984DC5EEE1B48');
        $this->addSql('DROP INDEX IDX_E94984DC634DFEB ON walk_registration');
        $this->addSql('DROP INDEX IDX_E94984DC5EEE1B48 ON walk_registration');
        $this->addSql('ALTER TABLE walk_registration DROP dog_id, DROP walk_id');
    }
}
