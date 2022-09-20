<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220919045811 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ticket_selected (id INT AUTO_INCREMENT NOT NULL, adult SMALLINT NOT NULL, kid SMALLINT DEFAULT NULL, infant SMALLINT DEFAULT NULL, price_total DOUBLE PRECISION NOT NULL, ip_address VARCHAR(64) DEFAULT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_by INT DEFAULT NULL, updated_at DATETIME DEFAULT NULL, updated_by INT DEFAULT NULL, status SMALLINT NOT NULL, from_where VARCHAR(5) NOT NULL, to_where VARCHAR(5) NOT NULL, departing_date DATE NOT NULL, return_departing_date DATE DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE search_ticket CHANGE price_total price_total DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA39599BECF');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA39599BECF FOREIGN KEY (ticket_selected_id_id) REFERENCES ticket_selected (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA39599BECF');
        $this->addSql('DROP TABLE ticket_selected');
        $this->addSql('ALTER TABLE search_ticket CHANGE price_total price_total DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA39599BECF');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA39599BECF FOREIGN KEY (ticket_selected_id_id) REFERENCES search_ticket (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
