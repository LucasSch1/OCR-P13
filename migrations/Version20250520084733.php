<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250520084733 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE cart (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, status VARCHAR(255) NOT NULL, INDEX IDX_BA388B7A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE cart_products (id INT AUTO_INCREMENT NOT NULL, cart_id INT NOT NULL, product_id INT NOT NULL, quantity INT NOT NULL, unit_price DOUBLE PRECISION NOT NULL, INDEX IDX_2D2515311AD5CDBF (cart_id), INDEX IDX_2D2515314584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE customer_order (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, order_date DATE NOT NULL, order_price NUMERIC(10, 2) NOT NULL, is_validated TINYINT(1) NOT NULL, INDEX IDX_3B1CE6A3A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE order_product (id INT AUTO_INCREMENT NOT NULL, order_id INT NOT NULL, product_id INT NOT NULL, quantity INT NOT NULL, unit_price NUMERIC(10, 2) NOT NULL, INDEX IDX_2530ADE68D9F6D38 (order_id), INDEX IDX_2530ADE64584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, long_description LONGTEXT NOT NULL, short_description VARCHAR(128) NOT NULL, product_price VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, last_name VARCHAR(128) NOT NULL, first_name VARCHAR(128) NOT NULL, email VARCHAR(255) NOT NULL, roles JSON NOT NULL COMMENT '(DC2Type:json)', password VARCHAR(255) NOT NULL, api_access TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', available_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', delivered_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart ADD CONSTRAINT FK_BA388B7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart_products ADD CONSTRAINT FK_2D2515311AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart_products ADD CONSTRAINT FK_2D2515314584665A FOREIGN KEY (product_id) REFERENCES product (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE customer_order ADD CONSTRAINT FK_3B1CE6A3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_product ADD CONSTRAINT FK_2530ADE68D9F6D38 FOREIGN KEY (order_id) REFERENCES customer_order (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_product ADD CONSTRAINT FK_2530ADE64584665A FOREIGN KEY (product_id) REFERENCES product (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE cart DROP FOREIGN KEY FK_BA388B7A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart_products DROP FOREIGN KEY FK_2D2515311AD5CDBF
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart_products DROP FOREIGN KEY FK_2D2515314584665A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE customer_order DROP FOREIGN KEY FK_3B1CE6A3A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_product DROP FOREIGN KEY FK_2530ADE68D9F6D38
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_product DROP FOREIGN KEY FK_2530ADE64584665A
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE cart
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE cart_products
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE customer_order
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE order_product
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE product
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
    }
}
