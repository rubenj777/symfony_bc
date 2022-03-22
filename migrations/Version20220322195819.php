<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220322195819 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_item_order DROP FOREIGN KEY FK_6C54751DE415FB15');
        $this->addSql('ALTER TABLE order_item_product DROP FOREIGN KEY FK_DCD7B693E415FB15');
        $this->addSql('DROP TABLE order_item');
        $this->addSql('DROP TABLE order_item_order');
        $this->addSql('DROP TABLE order_item_product');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE order_item (id INT AUTO_INCREMENT NOT NULL, quantity INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE order_item_order (order_item_id INT NOT NULL, order_id INT NOT NULL, INDEX IDX_6C54751DE415FB15 (order_item_id), INDEX IDX_6C54751D8D9F6D38 (order_id), PRIMARY KEY(order_item_id, order_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE order_item_product (order_item_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_DCD7B693E415FB15 (order_item_id), INDEX IDX_DCD7B6934584665A (product_id), PRIMARY KEY(order_item_id, product_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE order_item_order ADD CONSTRAINT FK_6C54751DE415FB15 FOREIGN KEY (order_item_id) REFERENCES order_item (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE order_item_order ADD CONSTRAINT FK_6C54751D8D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE order_item_product ADD CONSTRAINT FK_DCD7B693E415FB15 FOREIGN KEY (order_item_id) REFERENCES order_item (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE order_item_product ADD CONSTRAINT FK_DCD7B6934584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
    }
}
