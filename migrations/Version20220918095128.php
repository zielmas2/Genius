<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220918095128 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ticket_customer (id INT AUTO_INCREMENT NOT NULL, ticket_id_id INT DEFAULT NULL, ticket_customer INT DEFAULT NULL, class VARCHAR(16) DEFAULT NULL, customer_type SMALLINT DEFAULT NULL, identity_no VARCHAR(32) DEFAULT NULL, name VARCHAR(64) NOT NULL, surname VARCHAR(64) DEFAULT NULL, gender SMALLINT DEFAULT NULL, date_of_birth DATE DEFAULT NULL, baggage VARCHAR(32) DEFAULT NULL, price DOUBLE PRECISION DEFAULT NULL, tax DOUBLE PRECISION DEFAULT NULL, price_commission DOUBLE PRECISION DEFAULT NULL, price_total DOUBLE PRECISION DEFAULT NULL, email VARCHAR(96) DEFAULT NULL, phone VARCHAR(19) DEFAULT NULL, seat_no VARCHAR(9) DEFAULT NULL, pnr_no VARCHAR(16) DEFAULT NULL, ip_address VARCHAR(64) DEFAULT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_by INT DEFAULT NULL, updated_at DATETIME DEFAULT NULL, updated_by INT DEFAULT NULL, status VARCHAR(1) NOT NULL, INDEX IDX_45072885774FDDC (ticket_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ticket_customer ADD CONSTRAINT FK_45072885774FDDC FOREIGN KEY (ticket_id_id) REFERENCES ticket (id)');
        $this->addSql('ALTER TABLE ticket CHANGE status status SMALLINT NOT NULL');
        $this->addSql('ALTER TABLE ticket_selected CHANGE status status SMALLINT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ticket_customer DROP FOREIGN KEY FK_45072885774FDDC');
        $this->addSql('DROP TABLE ticket_customer');
        $this->addSql('ALTER TABLE ticket_selected CHANGE status status SMALLINT DEFAULT 1 NOT NULL');
        $this->addSql('ALTER TABLE ticket CHANGE status status SMALLINT DEFAULT 1 NOT NULL');
    }
}
