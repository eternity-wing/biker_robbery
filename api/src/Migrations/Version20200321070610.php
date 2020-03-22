<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200321070610 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('postgresql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE police_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE bike_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE police (id INT NOT NULL, personal_code VARCHAR(30) NOT NULL, full_name VARCHAR(255) NOT NULL, is_available BOOLEAN DEFAULT \'true\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E47C5959461F37A5 ON police (personal_code)');
        $this->addSql('CREATE TABLE bike (id INT NOT NULL, responsible_id INT DEFAULT NULL, license_number VARCHAR(255) NOT NULL, color VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, owner_full_name VARCHAR(255) NOT NULL, stealing_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, stealing_description TEXT NOT NULL, is_resolved BOOLEAN DEFAULT \'false\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4CBC3780EC7E7152 ON bike (license_number)');
        $this->addSql('CREATE INDEX IDX_4CBC3780602AD315 ON bike (responsible_id)');
        $this->addSql('ALTER TABLE bike ADD CONSTRAINT FK_4CBC3780602AD315 FOREIGN KEY (responsible_id) REFERENCES police (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('postgresql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE bike DROP CONSTRAINT FK_4CBC3780602AD315');
        $this->addSql('DROP SEQUENCE police_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE bike_id_seq CASCADE');
        $this->addSql('DROP TABLE police');
        $this->addSql('DROP TABLE bike');
    }
}
