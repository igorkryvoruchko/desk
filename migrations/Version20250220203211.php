<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250220203211 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE company (id INT AUTO_INCREMENT NOT NULL, alias VARCHAR(255) NOT NULL, logo VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_4FBF094FE16C6B94 (alias), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE company_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, locale VARCHAR(5) NOT NULL, INDEX IDX_ADB8FDF72C2AC5D3 (translatable_id), UNIQUE INDEX company_translation_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE kind_menu (id INT AUTO_INCREMENT NOT NULL, restaurant_id INT NOT NULL, alias VARCHAR(255) NOT NULL, is_active TINYINT(1) NOT NULL, INDEX IDX_59757726B1E7706E (restaurant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE kind_menu_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, locale VARCHAR(5) NOT NULL, INDEX IDX_45077082C2AC5D3 (translatable_id), UNIQUE INDEX kind_menu_translation_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menu_item (id INT AUTO_INCREMENT NOT NULL, kind_menu_id INT DEFAULT NULL, alias VARCHAR(255) NOT NULL, quantity INT NOT NULL, photo VARCHAR(255) DEFAULT NULL, price DOUBLE PRECISION NOT NULL, special_price DOUBLE PRECISION DEFAULT NULL, INDEX IDX_D754D5501A9095B9 (kind_menu_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menu_item_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, locale VARCHAR(5) NOT NULL, INDEX IDX_683EE3A62C2AC5D3 (translatable_id), UNIQUE INDEX menu_item_translation_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE restaurant (id INT AUTO_INCREMENT NOT NULL, company_id INT NOT NULL, alias VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_EB95123F979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE restaurant_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, locale VARCHAR(5) NOT NULL, INDEX IDX_E5127F752C2AC5D3 (translatable_id), UNIQUE INDEX restaurant_translation_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `table` (id INT AUTO_INCREMENT NOT NULL, zone_id INT NOT NULL, number INT NOT NULL, seats_count SMALLINT NOT NULL, INDEX IDX_F6298F469F2C3FAB (zone_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, locale VARCHAR(255) DEFAULT NULL, location VARCHAR(255) DEFAULT NULL, rating SMALLINT NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE zone (id INT AUTO_INCREMENT NOT NULL, restaurant_id INT NOT NULL, alias VARCHAR(255) NOT NULL, INDEX IDX_A0EBC007B1E7706E (restaurant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE zone_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, locale VARCHAR(5) NOT NULL, INDEX IDX_144346042C2AC5D3 (translatable_id), UNIQUE INDEX zone_translation_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE company_translation ADD CONSTRAINT FK_ADB8FDF72C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES company (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE kind_menu ADD CONSTRAINT FK_59757726B1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id)');
        $this->addSql('ALTER TABLE kind_menu_translation ADD CONSTRAINT FK_45077082C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES kind_menu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menu_item ADD CONSTRAINT FK_D754D5501A9095B9 FOREIGN KEY (kind_menu_id) REFERENCES kind_menu (id)');
        $this->addSql('ALTER TABLE menu_item_translation ADD CONSTRAINT FK_683EE3A62C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES menu_item (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE restaurant ADD CONSTRAINT FK_EB95123F979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE restaurant_translation ADD CONSTRAINT FK_E5127F752C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES restaurant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE `table` ADD CONSTRAINT FK_F6298F469F2C3FAB FOREIGN KEY (zone_id) REFERENCES zone (id)');
        $this->addSql('ALTER TABLE zone ADD CONSTRAINT FK_A0EBC007B1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id)');
        $this->addSql('ALTER TABLE zone_translation ADD CONSTRAINT FK_144346042C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES zone (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE company_translation DROP FOREIGN KEY FK_ADB8FDF72C2AC5D3');
        $this->addSql('ALTER TABLE kind_menu DROP FOREIGN KEY FK_59757726B1E7706E');
        $this->addSql('ALTER TABLE kind_menu_translation DROP FOREIGN KEY FK_45077082C2AC5D3');
        $this->addSql('ALTER TABLE menu_item DROP FOREIGN KEY FK_D754D5501A9095B9');
        $this->addSql('ALTER TABLE menu_item_translation DROP FOREIGN KEY FK_683EE3A62C2AC5D3');
        $this->addSql('ALTER TABLE restaurant DROP FOREIGN KEY FK_EB95123F979B1AD6');
        $this->addSql('ALTER TABLE restaurant_translation DROP FOREIGN KEY FK_E5127F752C2AC5D3');
        $this->addSql('ALTER TABLE `table` DROP FOREIGN KEY FK_F6298F469F2C3FAB');
        $this->addSql('ALTER TABLE zone DROP FOREIGN KEY FK_A0EBC007B1E7706E');
        $this->addSql('ALTER TABLE zone_translation DROP FOREIGN KEY FK_144346042C2AC5D3');
        $this->addSql('DROP TABLE company');
        $this->addSql('DROP TABLE company_translation');
        $this->addSql('DROP TABLE kind_menu');
        $this->addSql('DROP TABLE kind_menu_translation');
        $this->addSql('DROP TABLE menu_item');
        $this->addSql('DROP TABLE menu_item_translation');
        $this->addSql('DROP TABLE restaurant');
        $this->addSql('DROP TABLE restaurant_translation');
        $this->addSql('DROP TABLE `table`');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE zone');
        $this->addSql('DROP TABLE zone_translation');
    }
}
