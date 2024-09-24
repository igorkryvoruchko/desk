<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240924190844 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE zone (id INT AUTO_INCREMENT NOT NULL, restaurant_id INT NOT NULL, alias VARCHAR(255) NOT NULL, INDEX IDX_A0EBC007B1E7706E (restaurant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE zone_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, locale VARCHAR(5) NOT NULL, INDEX IDX_144346042C2AC5D3 (translatable_id), UNIQUE INDEX zone_translation_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE zone ADD CONSTRAINT FK_A0EBC007B1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id)');
        $this->addSql('ALTER TABLE zone_translation ADD CONSTRAINT FK_144346042C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES zone (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE zone DROP FOREIGN KEY FK_A0EBC007B1E7706E');
        $this->addSql('ALTER TABLE zone_translation DROP FOREIGN KEY FK_144346042C2AC5D3');
        $this->addSql('DROP TABLE zone');
        $this->addSql('DROP TABLE zone_translation');
    }
}
