<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220117104442 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE club ADD skatepark_id INT DEFAULT NULL, ADD cover_filename VARCHAR(255) DEFAULT NULL, ADD area VARCHAR(255) NOT NULL, CHANGE zippcode zippcode INT NOT NULL');
        $this->addSql('ALTER TABLE club ADD CONSTRAINT FK_B8EE387289600404 FOREIGN KEY (skatepark_id) REFERENCES skatepark (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_B8EE387289600404 ON club (skatepark_id)');
        $this->addSql('ALTER TABLE session ADD addrees VARCHAR(255) NOT NULL, ADD area VARCHAR(255) NOT NULL, CHANGE town town VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE shop ADD area VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE skatepark CHANGE content content VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE club DROP FOREIGN KEY FK_B8EE387289600404');
        $this->addSql('DROP INDEX IDX_B8EE387289600404 ON club');
        $this->addSql('ALTER TABLE club DROP skatepark_id, DROP cover_filename, DROP area, CHANGE zippcode zippcode VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE session DROP addrees, DROP area, CHANGE town town INT NOT NULL');
        $this->addSql('ALTER TABLE shop DROP area');
        $this->addSql('ALTER TABLE skatepark CHANGE content content VARCHAR(1000) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
