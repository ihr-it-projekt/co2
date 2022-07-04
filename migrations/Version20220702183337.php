<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220702183337 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE alert (id INT AUTO_INCREMENT NOT NULL, sensor_id INT NOT NULL, start_time DATETIME NOT NULL, end_time DATETIME NOT NULL, measurement1 INT NOT NULL, measurement2 INT NOT NULL, measurement3 INT NOT NULL, INDEX IDX_17FD46C1A247991F (sensor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE measurement (id INT AUTO_INCREMENT NOT NULL, sensor_id INT NOT NULL, co2 INT NOT NULL, time DATETIME NOT NULL, INDEX IDX_2CE0D811A247991F (sensor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sensor (id INT AUTO_INCREMENT NOT NULL, status VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE alert ADD CONSTRAINT FK_17FD46C1A247991F FOREIGN KEY (sensor_id) REFERENCES sensor (id)');
        $this->addSql('ALTER TABLE measurement ADD CONSTRAINT FK_2CE0D811A247991F FOREIGN KEY (sensor_id) REFERENCES sensor (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE alert DROP FOREIGN KEY FK_17FD46C1A247991F');
        $this->addSql('ALTER TABLE measurement DROP FOREIGN KEY FK_2CE0D811A247991F');
        $this->addSql('DROP TABLE alert');
        $this->addSql('DROP TABLE measurement');
        $this->addSql('DROP TABLE sensor');
    }
}
