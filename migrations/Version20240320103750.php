<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240320103750 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE gamelog (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(15) NOT NULL, hands INTEGER NOT NULL, balance DOUBLE PRECISION NOT NULL, time TIMESTAMP DEFAULT CURRENT_TIMESTAMP)');
        $this->addSql('CREATE TABLE roundlog (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, gamelog_id INTEGER, winhand INTEGER NOT NULL, difference DOUBLE PRECISION NOT NULL, balance DOUBLE PRECISION NOT NULL, time TIMESTAMP DEFAULT CURRENT_TIMESTAMP, FOREIGN KEY (gamelog_id) REFERENCES gamelog(id))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE gamelog');
        $this->addSql('DROP TABLE roundlog');
    }
}
