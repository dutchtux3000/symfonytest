<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190611172221 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, firstname, lastname, email, birthday FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, firstname VARCHAR(255) NOT NULL COLLATE BINARY, lastname VARCHAR(255) NOT NULL COLLATE BINARY, email VARCHAR(255) NOT NULL COLLATE BINARY, birthday DATE DEFAULT NULL)');
        $this->addSql('INSERT INTO user (id, firstname, lastname, email, birthday) SELECT id, firstname, lastname, email, birthday FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('DROP INDEX IDX_715F0007233D34C1');
        $this->addSql('DROP INDEX IDX_715F00073AD8644E');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user_following AS SELECT user_source, user_target FROM user_following');
        $this->addSql('DROP TABLE user_following');
        $this->addSql('CREATE TABLE user_following (user_source INTEGER NOT NULL, user_target INTEGER NOT NULL, PRIMARY KEY(user_source, user_target), CONSTRAINT FK_715F00073AD8644E FOREIGN KEY (user_source) REFERENCES user (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_715F0007233D34C1 FOREIGN KEY (user_target) REFERENCES user (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO user_following (user_source, user_target) SELECT user_source, user_target FROM __temp__user_following');
        $this->addSql('DROP TABLE __temp__user_following');
        $this->addSql('CREATE INDEX IDX_715F0007233D34C1 ON user_following (user_target)');
        $this->addSql('CREATE INDEX IDX_715F00073AD8644E ON user_following (user_source)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, firstname, lastname, email, birthday FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, birthday DATETIME DEFAULT NULL)');
        $this->addSql('INSERT INTO user (id, firstname, lastname, email, birthday) SELECT id, firstname, lastname, email, birthday FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('DROP INDEX IDX_715F00073AD8644E');
        $this->addSql('DROP INDEX IDX_715F0007233D34C1');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user_following AS SELECT user_source, user_target FROM user_following');
        $this->addSql('DROP TABLE user_following');
        $this->addSql('CREATE TABLE user_following (user_source INTEGER NOT NULL, user_target INTEGER NOT NULL, PRIMARY KEY(user_source, user_target))');
        $this->addSql('INSERT INTO user_following (user_source, user_target) SELECT user_source, user_target FROM __temp__user_following');
        $this->addSql('DROP TABLE __temp__user_following');
        $this->addSql('CREATE INDEX IDX_715F00073AD8644E ON user_following (user_source)');
        $this->addSql('CREATE INDEX IDX_715F0007233D34C1 ON user_following (user_target)');
    }
}
