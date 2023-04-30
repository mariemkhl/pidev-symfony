<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230430110627 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commander_produit (idp INT AUTO_INCREMENT NOT NULL, produitchoisi INT NOT NULL, quantite INT NOT NULL, PRIMARY KEY(idp)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE commander produit');
        $this->addSql('ALTER TABLE map_art CHANGE nblikes nblikes INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commander produit (idp INT AUTO_INCREMENT NOT NULL, produitchoisi INT NOT NULL, quantite INT NOT NULL, PRIMARY KEY(idp)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE commander_produit');
        $this->addSql('ALTER TABLE map_art CHANGE nblikes nblikes INT DEFAULT 0 NOT NULL');
    }
}
