<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230331094455 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE brand (id INT AUTO_INCREMENT NOT NULL, uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE camera (id INT AUTO_INCREMENT NOT NULL, uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(255) NOT NULL, numeric_zoom INT NOT NULL, resolution INT NOT NULL, numeric_zoom_back TINYINT(1) NOT NULL, flash TINYINT(1) NOT NULL, flash_back TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE color (id INT AUTO_INCREMENT NOT NULL, uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(255) NOT NULL, hex VARCHAR(20) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE customer (id INT AUTO_INCREMENT NOT NULL, uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, phone_number VARCHAR(255) NOT NULL, street VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, post_code VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE customer_reseller (customer_id INT NOT NULL, reseller_id INT NOT NULL, INDEX IDX_34C41BB69395C3F3 (customer_id), INDEX IDX_34C41BB691E6A19D (reseller_id), PRIMARY KEY(customer_id, reseller_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE picture (id INT AUTO_INCREMENT NOT NULL, smartphone_id INT NOT NULL, uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', file_name VARCHAR(255) NOT NULL, INDEX IDX_16DB4F892E4F4908 (smartphone_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `range` (id INT AUTO_INCREMENT NOT NULL, brand_id INT NOT NULL, uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_93875A4944F5D008 (brand_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reseller (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', company VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_18015899E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE screen (id INT AUTO_INCREMENT NOT NULL, uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', resolution_main_screen VARCHAR(255) NOT NULL, diagonal INT NOT NULL, touch_screen TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE smartphone (id INT AUTO_INCREMENT NOT NULL, range_id INT NOT NULL, screen_id INT NOT NULL, uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(255) NOT NULL, price NUMERIC(7, 2) NOT NULL, started_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ended_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', technology VARCHAR(255) NOT NULL, operating_system VARCHAR(255) NOT NULL, specific_absorption_rate_member INT NOT NULL, specific_absorption_rate_trunk INT NOT NULL, specific_absorption_rate_head INT NOT NULL, weight INT NOT NULL, width INT NOT NULL, height INT NOT NULL, depth INT NOT NULL, spare_parts_availibility INT NOT NULL, index_repairibility INT NOT NULL, eco_rating_durability INT NOT NULL, eco_rating_climate_respect INT NOT NULL, eco_rating_repairability INT NOT NULL, eco_rating_resources_preservation INT NOT NULL, eco_rating_recyclability INT NOT NULL, micro_sd_slot_memory TINYINT(1) NOT NULL, rom_memory INT NOT NULL, call_autonomy INT NOT NULL, battery_autonomy INT NOT NULL, INDEX IDX_26B07E2E2A82D0B1 (range_id), INDEX IDX_26B07E2E41A67722 (screen_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE smartphone_camera (smartphone_id INT NOT NULL, camera_id INT NOT NULL, INDEX IDX_F91BDF002E4F4908 (smartphone_id), INDEX IDX_F91BDF00B47685CD (camera_id), PRIMARY KEY(smartphone_id, camera_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE smartphone_color (smartphone_id INT NOT NULL, color_id INT NOT NULL, INDEX IDX_B698EA552E4F4908 (smartphone_id), INDEX IDX_B698EA557ADA1FB5 (color_id), PRIMARY KEY(smartphone_id, color_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE customer_reseller ADD CONSTRAINT FK_34C41BB69395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE customer_reseller ADD CONSTRAINT FK_34C41BB691E6A19D FOREIGN KEY (reseller_id) REFERENCES reseller (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE picture ADD CONSTRAINT FK_16DB4F892E4F4908 FOREIGN KEY (smartphone_id) REFERENCES smartphone (id)');
        $this->addSql('ALTER TABLE `range` ADD CONSTRAINT FK_93875A4944F5D008 FOREIGN KEY (brand_id) REFERENCES brand (id)');
        $this->addSql('ALTER TABLE smartphone ADD CONSTRAINT FK_26B07E2E2A82D0B1 FOREIGN KEY (range_id) REFERENCES `range` (id)');
        $this->addSql('ALTER TABLE smartphone ADD CONSTRAINT FK_26B07E2E41A67722 FOREIGN KEY (screen_id) REFERENCES screen (id)');
        $this->addSql('ALTER TABLE smartphone_camera ADD CONSTRAINT FK_F91BDF002E4F4908 FOREIGN KEY (smartphone_id) REFERENCES smartphone (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE smartphone_camera ADD CONSTRAINT FK_F91BDF00B47685CD FOREIGN KEY (camera_id) REFERENCES camera (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE smartphone_color ADD CONSTRAINT FK_B698EA552E4F4908 FOREIGN KEY (smartphone_id) REFERENCES smartphone (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE smartphone_color ADD CONSTRAINT FK_B698EA557ADA1FB5 FOREIGN KEY (color_id) REFERENCES color (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer_reseller DROP FOREIGN KEY FK_34C41BB69395C3F3');
        $this->addSql('ALTER TABLE customer_reseller DROP FOREIGN KEY FK_34C41BB691E6A19D');
        $this->addSql('ALTER TABLE picture DROP FOREIGN KEY FK_16DB4F892E4F4908');
        $this->addSql('ALTER TABLE `range` DROP FOREIGN KEY FK_93875A4944F5D008');
        $this->addSql('ALTER TABLE smartphone DROP FOREIGN KEY FK_26B07E2E2A82D0B1');
        $this->addSql('ALTER TABLE smartphone DROP FOREIGN KEY FK_26B07E2E41A67722');
        $this->addSql('ALTER TABLE smartphone_camera DROP FOREIGN KEY FK_F91BDF002E4F4908');
        $this->addSql('ALTER TABLE smartphone_camera DROP FOREIGN KEY FK_F91BDF00B47685CD');
        $this->addSql('ALTER TABLE smartphone_color DROP FOREIGN KEY FK_B698EA552E4F4908');
        $this->addSql('ALTER TABLE smartphone_color DROP FOREIGN KEY FK_B698EA557ADA1FB5');
        $this->addSql('DROP TABLE brand');
        $this->addSql('DROP TABLE camera');
        $this->addSql('DROP TABLE color');
        $this->addSql('DROP TABLE customer');
        $this->addSql('DROP TABLE customer_reseller');
        $this->addSql('DROP TABLE picture');
        $this->addSql('DROP TABLE `range`');
        $this->addSql('DROP TABLE reseller');
        $this->addSql('DROP TABLE screen');
        $this->addSql('DROP TABLE smartphone');
        $this->addSql('DROP TABLE smartphone_camera');
        $this->addSql('DROP TABLE smartphone_color');
    }
}
