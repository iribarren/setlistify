<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190701143619 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE setlist (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, venue VARCHAR(255) NOT NULL, date DATE NOT NULL, spotify_id VARCHAR(255) NOT NULL, setlistfm_id VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE INDEX IDX_710BEA2AA76ED395 ON setlist (user_id)');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
        $this->addSql('CREATE TABLE song (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, artist VARCHAR(255) NOT NULL, album VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE song_setlist (song_id INTEGER NOT NULL, setlist_id INTEGER NOT NULL, PRIMARY KEY(song_id, setlist_id))');
        $this->addSql('CREATE INDEX IDX_7C60FB05A0BDB2F3 ON song_setlist (song_id)');
        $this->addSql('CREATE INDEX IDX_7C60FB0560D8C499 ON song_setlist (setlist_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE setlist');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE song');
        $this->addSql('DROP TABLE song_setlist');
    }
}
