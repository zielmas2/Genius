<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220917115118 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ticket (id INT AUTO_INCREMENT NOT NULL, ticket_selected_id_id INT DEFAULT NULL, airline VARCHAR(255) DEFAULT NULL, airline_code VARCHAR(6) NOT NULL, flight_number VARCHAR(16) NOT NULL, aircraft_type VARCHAR(32) DEFAULT NULL, baggage VARCHAR(255) DEFAULT NULL, flight_id VARCHAR(96) DEFAULT NULL, flight_time VARCHAR(96) DEFAULT NULL, adult_price DOUBLE PRECISION NOT NULL, price_kid DOUBLE PRECISION DEFAULT NULL, price_inf DOUBLE PRECISION DEFAULT NULL, tax DOUBLE PRECISION DEFAULT NULL, currency VARCHAR(3) NOT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_by INT DEFAULT NULL, updated_at DATETIME DEFAULT NULL, updated_by INT DEFAULT NULL, status SMALLINT NOT NULL, UNIQUE INDEX UNIQ_97A0ADA39599BECF (ticket_selected_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ticket_selected (id INT AUTO_INCREMENT NOT NULL, adult SMALLINT NOT NULL, kid SMALLINT DEFAULT NULL, infant SMALLINT DEFAULT NULL, price_total DOUBLE PRECISION NOT NULL, ip_address VARCHAR(64) DEFAULT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_by INT DEFAULT NULL, updated_at DATETIME DEFAULT NULL, updated_by INT DEFAULT NULL, status SMALLINT NOT NULL, from_where VARCHAR(5) NOT NULL, to_where VARCHAR(5) NOT NULL, departing_date DATE NOT NULL, return_departing_date DATE DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA39599BECF FOREIGN KEY (ticket_selected_id_id) REFERENCES ticket_selected (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA39599BECF');
        $this->addSql('DROP TABLE ticket');
        $this->addSql('DROP TABLE ticket_selected');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
