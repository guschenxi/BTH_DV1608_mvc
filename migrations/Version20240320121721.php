<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240320121721 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE roundlog');
        //$this->addSql('CREATE TEMPORARY TABLE __temp__gamelog AS SELECT id, name, hands, balance FROM gamelog');
        $this->addSql('DROP TABLE gamelog');
        $this->addSql('CREATE TABLE gamelog (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(15) NOT NULL, hands INTEGER NOT NULL, balance DOUBLE PRECISION NOT NULL, timestamp DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL)');
        //$this->addSql('INSERT INTO gamelog (id, name, hands, balance) SELECT id, name, hands, balance FROM __temp__gamelog');
        //$this->addSql('DROP TABLE __temp__gamelog');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE roundlog (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, gamelog_id INTEGER DEFAULT NULL, winhand INTEGER NOT NULL, difference DOUBLE PRECISION NOT NULL, balance DOUBLE PRECISION NOT NULL, time DATETIME DEFAULT CURRENT_TIMESTAMP, FOREIGN KEY (gamelog_id) REFERENCES gamelog (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_4F7EF155526CC895 ON roundlog (gamelog_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__gamelog AS SELECT id, name, hands, balance FROM gamelog');
        $this->addSql('DROP TABLE gamelog');
        $this->addSql('CREATE TABLE gamelog (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(15) NOT NULL, hands INTEGER NOT NULL, balance DOUBLE PRECISION NOT NULL, time DATETIME DEFAULT CURRENT_TIMESTAMP)');
        $this->addSql('INSERT INTO gamelog (id, name, hands, balance) SELECT id, name, hands, balance FROM __temp__gamelog');
        $this->addSql('DROP TABLE __temp__gamelog');
    }
}
