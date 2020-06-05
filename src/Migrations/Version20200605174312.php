<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200605174312 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE items (id INT NOT NULL, name TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE posts (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE files (id INT NOT NULL, id_post INT DEFAULT NULL, filename VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6354059D1AA708F ON files (id_post)');
        $this->addSql('CREATE TABLE sections (id INT NOT NULL, name TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE words (id INT NOT NULL, name TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE item_word (id INT NOT NULL, id_word INT DEFAULT NULL, id_item INT DEFAULT NULL, cnt INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_BC131EA48D16913 ON item_word (id_word)');
        $this->addSql('CREATE INDEX IDX_BC131EA943B391C ON item_word (id_item)');
        $this->addSql('CREATE TABLE instructions (id INT NOT NULL, id_post INT DEFAULT NULL, id_item INT DEFAULT NULL, id_section INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_997D812BD1AA708F ON instructions (id_post)');
        $this->addSql('CREATE INDEX IDX_997D812B943B391C ON instructions (id_item)');
        $this->addSql('CREATE INDEX IDX_997D812BF3EED39F ON instructions (id_section)');
        $this->addSql('ALTER TABLE files ADD CONSTRAINT FK_6354059D1AA708F FOREIGN KEY (id_post) REFERENCES posts (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE item_word ADD CONSTRAINT FK_BC131EA48D16913 FOREIGN KEY (id_word) REFERENCES words (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE item_word ADD CONSTRAINT FK_BC131EA943B391C FOREIGN KEY (id_item) REFERENCES items (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE instructions ADD CONSTRAINT FK_997D812BD1AA708F FOREIGN KEY (id_post) REFERENCES posts (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE instructions ADD CONSTRAINT FK_997D812B943B391C FOREIGN KEY (id_item) REFERENCES items (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE instructions ADD CONSTRAINT FK_997D812BF3EED39F FOREIGN KEY (id_section) REFERENCES sections (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE item_word DROP CONSTRAINT FK_BC131EA943B391C');
        $this->addSql('ALTER TABLE instructions DROP CONSTRAINT FK_997D812B943B391C');
        $this->addSql('ALTER TABLE files DROP CONSTRAINT FK_6354059D1AA708F');
        $this->addSql('ALTER TABLE instructions DROP CONSTRAINT FK_997D812BD1AA708F');
        $this->addSql('ALTER TABLE instructions DROP CONSTRAINT FK_997D812BF3EED39F');
        $this->addSql('ALTER TABLE item_word DROP CONSTRAINT FK_BC131EA48D16913');
        $this->addSql('DROP TABLE items');
        $this->addSql('DROP TABLE posts');
        $this->addSql('DROP TABLE files');
        $this->addSql('DROP TABLE sections');
        $this->addSql('DROP TABLE words');
        $this->addSql('DROP TABLE item_word');
        $this->addSql('DROP TABLE instructions');
    }
}
