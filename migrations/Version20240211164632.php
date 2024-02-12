<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240211164632 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE participant (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME NOT NULL, attended TINYINT(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE modality CHANGE name name VARCHAR(255) DEFAULT NULL, CHANGE created_at created_at DATETIME DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE partner CHANGE updated_at updated_at DATETIME DEFAULT NULL, CHANGE logo logo VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE room CHANGE address address VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE training CHANGE name name VARCHAR(255) DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE training_organizer CHANGE created_at created_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE training_request CHANGE training_name training_name VARCHAR(255) DEFAULT NULL, CHANGE created_at created_at DATETIME DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE participant');
        $this->addSql('DROP TABLE user');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE modality CHANGE name name VARCHAR(255) DEFAULT \'NULL\', CHANGE created_at created_at DATETIME DEFAULT \'NULL\', CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE partner CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\', CHANGE logo logo VARCHAR(255) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE room CHANGE address address VARCHAR(255) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE training CHANGE name name VARCHAR(255) DEFAULT \'NULL\', CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE training_organizer CHANGE created_at created_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE training_request CHANGE training_name training_name VARCHAR(255) DEFAULT \'NULL\', CHANGE created_at created_at DATETIME DEFAULT \'NULL\', CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\'');
    }
}
