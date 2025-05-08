<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250507181048 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE city (id INT AUTO_INCREMENT NOT NULL, country_id INT NOT NULL, alias VARCHAR(255) NOT NULL, INDEX IDX_2D5B0234F92F3E70 (country_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE city_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT NOT NULL, name VARCHAR(255) NOT NULL, locale VARCHAR(255) NOT NULL, INDEX IDX_97DD5B602C2AC5D3 (translatable_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE company (id INT AUTO_INCREMENT NOT NULL, alias VARCHAR(255) NOT NULL, logo VARCHAR(255) DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_4FBF094FE16C6B94 (alias), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE company_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT NOT NULL, name VARCHAR(255) NOT NULL, locale VARCHAR(255) NOT NULL, INDEX IDX_ADB8FDF72C2AC5D3 (translatable_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE country (id INT AUTO_INCREMENT NOT NULL, alias VARCHAR(255) NOT NULL, deleted_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE country_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT NOT NULL, name VARCHAR(255) NOT NULL, locale VARCHAR(255) NOT NULL, INDEX IDX_A1FE6FA42C2AC5D3 (translatable_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE kind_menu (id INT AUTO_INCREMENT NOT NULL, restaurant_id INT NOT NULL, alias VARCHAR(255) NOT NULL, is_active TINYINT(1) NOT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_59757726B1E7706E (restaurant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE kind_menu_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, locale VARCHAR(255) NOT NULL, INDEX IDX_45077082C2AC5D3 (translatable_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE menu_item (id INT AUTO_INCREMENT NOT NULL, kind_menu_id INT DEFAULT NULL, alias VARCHAR(255) NOT NULL, quantity INT NOT NULL, photo VARCHAR(255) DEFAULT NULL, price DOUBLE PRECISION NOT NULL, special_price DOUBLE PRECISION DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_D754D5501A9095B9 (kind_menu_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE menu_item_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, locale VARCHAR(255) NOT NULL, INDEX IDX_683EE3A62C2AC5D3 (translatable_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, ordered_table_id INT NOT NULL, user_id INT NOT NULL, time_from DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', amount DOUBLE PRECISION DEFAULT NULL, comment VARCHAR(510) DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_F529939866AF5B23 (ordered_table_id), INDEX IDX_F5299398A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE order_menu_item (order_id INT NOT NULL, menu_item_id INT NOT NULL, INDEX IDX_6BD3AEA88D9F6D38 (order_id), INDEX IDX_6BD3AEA89AB44FE0 (menu_item_id), PRIMARY KEY(order_id, menu_item_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE restaurant (id INT AUTO_INCREMENT NOT NULL, company_id INT NOT NULL, city_id INT NOT NULL, alias VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, postal_code INT NOT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_EB95123F979B1AD6 (company_id), INDEX IDX_EB95123F8BAC62AF (city_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE restaurant_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, locale VARCHAR(255) NOT NULL, INDEX IDX_E5127F752C2AC5D3 (translatable_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE `table` (id INT AUTO_INCREMENT NOT NULL, zone_id INT NOT NULL, number INT NOT NULL, seats_count SMALLINT NOT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_F6298F469F2C3FAB (zone_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, company_id INT DEFAULT NULL, city_id INT NOT NULL, name VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, locale VARCHAR(255) DEFAULT NULL, rating SMALLINT NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D649979B1AD6 (company_id), INDEX IDX_8D93D6498BAC62AF (city_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE zone (id INT AUTO_INCREMENT NOT NULL, restaurant_id INT NOT NULL, alias VARCHAR(255) NOT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_A0EBC007B1E7706E (restaurant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE zone_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT NOT NULL, name VARCHAR(255) NOT NULL, locale VARCHAR(255) NOT NULL, INDEX IDX_144346042C2AC5D3 (translatable_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE city ADD CONSTRAINT FK_2D5B0234F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE city_translation ADD CONSTRAINT FK_97DD5B602C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES city (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE company_translation ADD CONSTRAINT FK_ADB8FDF72C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES company (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE country_translation ADD CONSTRAINT FK_A1FE6FA42C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES country (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE kind_menu ADD CONSTRAINT FK_59757726B1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE kind_menu_translation ADD CONSTRAINT FK_45077082C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES kind_menu (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE menu_item ADD CONSTRAINT FK_D754D5501A9095B9 FOREIGN KEY (kind_menu_id) REFERENCES kind_menu (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE menu_item_translation ADD CONSTRAINT FK_683EE3A62C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES menu_item (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `order` ADD CONSTRAINT FK_F529939866AF5B23 FOREIGN KEY (ordered_table_id) REFERENCES `table` (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `order` ADD CONSTRAINT FK_F5299398A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_menu_item ADD CONSTRAINT FK_6BD3AEA88D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_menu_item ADD CONSTRAINT FK_6BD3AEA89AB44FE0 FOREIGN KEY (menu_item_id) REFERENCES menu_item (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE restaurant ADD CONSTRAINT FK_EB95123F979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE restaurant ADD CONSTRAINT FK_EB95123F8BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE restaurant_translation ADD CONSTRAINT FK_E5127F752C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES restaurant (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `table` ADD CONSTRAINT FK_F6298F469F2C3FAB FOREIGN KEY (zone_id) REFERENCES zone (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user ADD CONSTRAINT FK_8D93D649979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) ON DELETE SET NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user ADD CONSTRAINT FK_8D93D6498BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE zone ADD CONSTRAINT FK_A0EBC007B1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE zone_translation ADD CONSTRAINT FK_144346042C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES zone (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE city DROP FOREIGN KEY FK_2D5B0234F92F3E70
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE city_translation DROP FOREIGN KEY FK_97DD5B602C2AC5D3
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE company_translation DROP FOREIGN KEY FK_ADB8FDF72C2AC5D3
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE country_translation DROP FOREIGN KEY FK_A1FE6FA42C2AC5D3
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE kind_menu DROP FOREIGN KEY FK_59757726B1E7706E
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE kind_menu_translation DROP FOREIGN KEY FK_45077082C2AC5D3
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE menu_item DROP FOREIGN KEY FK_D754D5501A9095B9
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE menu_item_translation DROP FOREIGN KEY FK_683EE3A62C2AC5D3
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `order` DROP FOREIGN KEY FK_F529939866AF5B23
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_menu_item DROP FOREIGN KEY FK_6BD3AEA88D9F6D38
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_menu_item DROP FOREIGN KEY FK_6BD3AEA89AB44FE0
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE restaurant DROP FOREIGN KEY FK_EB95123F979B1AD6
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE restaurant DROP FOREIGN KEY FK_EB95123F8BAC62AF
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE restaurant_translation DROP FOREIGN KEY FK_E5127F752C2AC5D3
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `table` DROP FOREIGN KEY FK_F6298F469F2C3FAB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user DROP FOREIGN KEY FK_8D93D649979B1AD6
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user DROP FOREIGN KEY FK_8D93D6498BAC62AF
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE zone DROP FOREIGN KEY FK_A0EBC007B1E7706E
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE zone_translation DROP FOREIGN KEY FK_144346042C2AC5D3
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE city
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE city_translation
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE company
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE company_translation
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE country
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE country_translation
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE kind_menu
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE kind_menu_translation
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE menu_item
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE menu_item_translation
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE `order`
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE order_menu_item
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE restaurant
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE restaurant_translation
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE `table`
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE zone
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE zone_translation
        SQL);
    }
}
