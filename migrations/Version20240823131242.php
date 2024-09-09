<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240823131242 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE certification (id INT AUTO_INCREMENT NOT NULL, instrument_id INT DEFAULT NULL, conditionsetalonnage_id INT DEFAULT NULL, dateverification DATE NOT NULL, numerocertificat VARCHAR(255) NOT NULL, dateemission DATE NOT NULL, validite DATE NOT NULL, INDEX IDX_6C3C6D75CF11D9C (instrument_id), INDEX IDX_6C3C6D7529FBB1B4 (conditionsetalonnage_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE conditionsetalonnage (id INT AUTO_INCREMENT NOT NULL, norme VARCHAR(255) NOT NULL, conditionenvironnemental VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE controledexcentration (id INT AUTO_INCREMENT NOT NULL, instrument_id INT DEFAULT NULL, numposition INT NOT NULL, valeurnominale NUMERIC(10, 2) DEFAULT NULL, indicationlue NUMERIC(10, 2) DEFAULT NULL, indicationsurcharge NUMERIC(10, 2) DEFAULT NULL, ecartreleve NUMERIC(10, 2) DEFAULT NULL, croquisetposition VARCHAR(255) DEFAULT NULL, excentrationcorrecte TINYINT(1) DEFAULT NULL, INDEX IDX_FDBBF2F8CF11D9C (instrument_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE controlefidelite (id INT AUTO_INCREMENT NOT NULL, instrument_id INT DEFAULT NULL, pointsdessai INT NOT NULL, valeurnominale NUMERIC(10, 2) NOT NULL, indicationlue NUMERIC(10, 2) NOT NULL, ecartreleve NUMERIC(10, 2) DEFAULT NULL, ecartmaximalreleve NUMERIC(10, 2) DEFAULT NULL, fidelitecorrecte TINYINT(1) DEFAULT NULL, INDEX IDX_8B9DE12FCF11D9C (instrument_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE controlejustesse (id INT AUTO_INCREMENT NOT NULL, instrument_id INT DEFAULT NULL, pointsdessai VARCHAR(255) DEFAULT NULL, valeurnominale NUMERIC(10, 2) DEFAULT NULL, valeurmonte NUMERIC(10, 2) DEFAULT NULL, valeurdescente NUMERIC(10, 2) DEFAULT NULL, indicationsurchage NUMERIC(10, 2) DEFAULT NULL, ecartreleve NUMERIC(10, 2) DEFAULT NULL, justessecorrecte TINYINT(1) DEFAULT NULL, sensibilitecorrecte TINYINT(1) DEFAULT NULL, INDEX IDX_1CE77120CF11D9C (instrument_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE instrument (id INT AUTO_INCREMENT NOT NULL, tolerance_id INT DEFAULT NULL, designation VARCHAR(255) NOT NULL, marque VARCHAR(255) NOT NULL, modele VARCHAR(255) NOT NULL, numeroserie VARCHAR(255) DEFAULT NULL, porteemax VARCHAR(255) NOT NULL, porteemini VARCHAR(255) NOT NULL, classeprecision VARCHAR(255) NOT NULL, echelonverification VARCHAR(255) NOT NULL, etat TINYINT(1) DEFAULT NULL, INDEX IDX_3CBF69DDF8BA9717 (tolerance_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tolerance (id INT AUTO_INCREMENT NOT NULL, valeur INT DEFAULT NULL, unitemesure VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE certification ADD CONSTRAINT FK_6C3C6D75CF11D9C FOREIGN KEY (instrument_id) REFERENCES instrument (id)');
        $this->addSql('ALTER TABLE certification ADD CONSTRAINT FK_6C3C6D7529FBB1B4 FOREIGN KEY (conditionsetalonnage_id) REFERENCES conditionsetalonnage (id)');
        $this->addSql('ALTER TABLE controledexcentration ADD CONSTRAINT FK_FDBBF2F8CF11D9C FOREIGN KEY (instrument_id) REFERENCES instrument (id)');
        $this->addSql('ALTER TABLE controlefidelite ADD CONSTRAINT FK_8B9DE12FCF11D9C FOREIGN KEY (instrument_id) REFERENCES instrument (id)');
        $this->addSql('ALTER TABLE controlejustesse ADD CONSTRAINT FK_1CE77120CF11D9C FOREIGN KEY (instrument_id) REFERENCES instrument (id)');
        $this->addSql('ALTER TABLE instrument ADD CONSTRAINT FK_3CBF69DDF8BA9717 FOREIGN KEY (tolerance_id) REFERENCES tolerance (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE certification DROP FOREIGN KEY FK_6C3C6D75CF11D9C');
        $this->addSql('ALTER TABLE certification DROP FOREIGN KEY FK_6C3C6D7529FBB1B4');
        $this->addSql('ALTER TABLE controledexcentration DROP FOREIGN KEY FK_FDBBF2F8CF11D9C');
        $this->addSql('ALTER TABLE controlefidelite DROP FOREIGN KEY FK_8B9DE12FCF11D9C');
        $this->addSql('ALTER TABLE controlejustesse DROP FOREIGN KEY FK_1CE77120CF11D9C');
        $this->addSql('ALTER TABLE instrument DROP FOREIGN KEY FK_3CBF69DDF8BA9717');
        $this->addSql('DROP TABLE certification');
        $this->addSql('DROP TABLE conditionsetalonnage');
        $this->addSql('DROP TABLE controledexcentration');
        $this->addSql('DROP TABLE controlefidelite');
        $this->addSql('DROP TABLE controlejustesse');
        $this->addSql('DROP TABLE instrument');
        $this->addSql('DROP TABLE tolerance');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
