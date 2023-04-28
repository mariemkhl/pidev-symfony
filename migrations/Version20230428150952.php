<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230428150952 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article (id_article INT AUTO_INCREMENT NOT NULL, titre_article VARCHAR(255) DEFAULT NULL, date_article DATE DEFAULT NULL, content_article VARCHAR(255) NOT NULL, nbrLikes_article INT DEFAULT NULL, image_article VARCHAR(255) DEFAULT NULL, category_article VARCHAR(255) NOT NULL, idUser INT DEFAULT NULL, PRIMARY KEY(id_article)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id_cat INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id_cat)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE collection (id_col INT AUTO_INCREMENT NOT NULL, nom_col VARCHAR(255) NOT NULL, id_p INT NOT NULL, nom_p VARCHAR(255) NOT NULL, PRIMARY KEY(id_col)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commande (idcommande INT AUTO_INCREMENT NOT NULL, prix_tot DOUBLE PRECISION NOT NULL, userid VARCHAR(255) NOT NULL, payment VARCHAR(255) NOT NULL, date_creation VARCHAR(255) NOT NULL, PRIMARY KEY(idcommande)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaire (id_commentaire INT AUTO_INCREMENT NOT NULL, id_article INT NOT NULL, content_commentaire VARCHAR(255) NOT NULL, date_commentaire DATE DEFAULT NULL, nb_likes_commentaire INT NOT NULL, etat_commentaire TINYINT(1) NOT NULL, id_user INT NOT NULL, INDEX id_article (id_article), PRIMARY KEY(id_commentaire)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE events (id_event INT AUTO_INCREMENT NOT NULL, nameEv VARCHAR(255) NOT NULL, date_event DATE NOT NULL, location VARCHAR(255) NOT NULL, id_user INT NOT NULL, categorie VARCHAR(255) NOT NULL, nbplacetotal INT NOT NULL, img VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id_event)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE likes (id INT AUTO_INCREMENT NOT NULL, RefL VARCHAR(120) NOT NULL, RefU INT NOT NULL, nomU VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE map_art (id INT AUTO_INCREMENT NOT NULL, nomplace VARCHAR(120) NOT NULL, description VARCHAR(300) NOT NULL, lien VARCHAR(120) NOT NULL, image VARCHAR(120) NOT NULL, nblikes INT NOT NULL, Latitude DOUBLE PRECISION NOT NULL, Longitude DOUBLE PRECISION NOT NULL, categorie VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE paiment (commande INT AUTO_INCREMENT NOT NULL, num_carte INT NOT NULL, nom_carte VARCHAR(255) NOT NULL, date_ex DATE NOT NULL, CV_code INT NOT NULL, prix_tot INT NOT NULL, PRIMARY KEY(commande)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id_p INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, prix DOUBLE PRECISION NOT NULL, img VARCHAR(255) DEFAULT NULL, cat_p VARCHAR(255) NOT NULL, user_id INT NOT NULL, url VARCHAR(255) NOT NULL, PRIMARY KEY(id_p)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reclamation (numero INT AUTO_INCREMENT NOT NULL, commentaire VARCHAR(255) NOT NULL, typeReclamation VARCHAR(255) NOT NULL, PRIMARY KEY(numero)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservations (id_res INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, dateRE DATE NOT NULL, PRIMARY KEY(id_res)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur (Id_user INT AUTO_INCREMENT NOT NULL, Username VARCHAR(50) NOT NULL, Password VARCHAR(50) NOT NULL, Email VARCHAR(50) NOT NULL, Address VARCHAR(100) DEFAULT NULL, domaine VARCHAR(100) DEFAULT NULL, Num_tel VARCHAR(100) NOT NULL, role VARCHAR(255) NOT NULL, PRIMARY KEY(Id_user)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE collection');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE events');
        $this->addSql('DROP TABLE likes');
        $this->addSql('DROP TABLE map_art');
        $this->addSql('DROP TABLE paiment');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE reclamation');
        $this->addSql('DROP TABLE reservations');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
