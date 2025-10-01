<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251001064723 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category CHANGE name name VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE comment CHANGE recipe_id recipe_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE favorite CHANGE recipe_id recipe_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE image CHANGE recipe_id recipe_id INT DEFAULT NULL, CHANGE alt_text alt_text VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE recipe CHANGE title title VARCHAR(200) NOT NULL, CHANGE ingredients ingredients LONGTEXT NOT NULL, CHANGE steps steps LONGTEXT NOT NULL, CHANGE prep_time prep_time INT NOT NULL, CHANGE cook_time cook_time INT NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE username username VARCHAR(50) DEFAULT NULL, CHANGE email email VARCHAR(180) NOT NULL, CHANGE password password VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON user (email)');
        $this->addSql('ALTER TABLE vote CHANGE value value INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE favorite CHANGE recipe_id recipe_id INT NOT NULL');
        $this->addSql('ALTER TABLE category CHANGE name name VARCHAR(200) NOT NULL');
        $this->addSql('ALTER TABLE comment CHANGE recipe_id recipe_id INT NOT NULL');
        $this->addSql('ALTER TABLE vote CHANGE value value SMALLINT NOT NULL');
        $this->addSql('ALTER TABLE recipe CHANGE title title VARCHAR(150) NOT NULL, CHANGE ingredients ingredients JSON NOT NULL, CHANGE steps steps JSON NOT NULL, CHANGE prep_time prep_time SMALLINT NOT NULL, CHANGE cook_time cook_time SMALLINT NOT NULL');
        $this->addSql('ALTER TABLE image CHANGE recipe_id recipe_id INT NOT NULL, CHANGE alt_text alt_text VARCHAR(255) DEFAULT NULL');
        $this->addSql('DROP INDEX UNIQ_IDENTIFIER_EMAIL ON user');
        $this->addSql('ALTER TABLE user CHANGE email email VARCHAR(100) NOT NULL, CHANGE password password VARCHAR(150) NOT NULL, CHANGE username username VARCHAR(50) NOT NULL');
    }
}
