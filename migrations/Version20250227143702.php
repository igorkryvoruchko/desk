<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250227143702 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, ordered_table_id INT NOT NULL, user_id INT NOT NULL, time_from DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', amount DOUBLE PRECISION DEFAULT NULL, comment VARCHAR(510) DEFAULT NULL, INDEX IDX_F529939866AF5B23 (ordered_table_id), INDEX IDX_F5299398A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_menu_item (order_id INT NOT NULL, menu_item_id INT NOT NULL, INDEX IDX_6BD3AEA88D9F6D38 (order_id), INDEX IDX_6BD3AEA89AB44FE0 (menu_item_id), PRIMARY KEY(order_id, menu_item_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F529939866AF5B23 FOREIGN KEY (ordered_table_id) REFERENCES `table` (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE order_menu_item ADD CONSTRAINT FK_6BD3AEA88D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE order_menu_item ADD CONSTRAINT FK_6BD3AEA89AB44FE0 FOREIGN KEY (menu_item_id) REFERENCES menu_item (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F529939866AF5B23');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398A76ED395');
        $this->addSql('ALTER TABLE order_menu_item DROP FOREIGN KEY FK_6BD3AEA88D9F6D38');
        $this->addSql('ALTER TABLE order_menu_item DROP FOREIGN KEY FK_6BD3AEA89AB44FE0');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE order_menu_item');
    }
}
