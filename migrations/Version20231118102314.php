<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231118102314 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $sql = <<<SQL
CREATE TABLE crawl_jobs (
    id BIGINT AUTO_INCREMENT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    url VARCHAR(700) NOT NULL,
    url_host VARCHAR(255) NOT NULL,
    type VARCHAR(255) NOT NULL,
    operation JSON NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY url (url)
)
SQL;
        $this->addSql($sql);

        $sql2 = <<<SQL
CREATE TABLE crawl_job_tasks (
    id BIGINT AUTO_INCREMENT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    job_id BIGINT NOT NULL,
    name VARCHAR(255) NOT NULL,
    url VARCHAR(700) NOT NULL,
    url_host VARCHAR(255) NOT NULL,
    type VARCHAR(255) NOT NULL,
    operation JSON NOT NULL,
    state VARCHAR(50) NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX job_id (job_id),
    INDEX state (state)
)
SQL;
        $this->addSql($sql2);

        $sql3 = <<<SQL
CREATE TABLE download_limits (
    id BIGINT AUTO_INCREMENT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    url_host VARCHAR(255) NOT NULL,
    suspended_until DATETIME NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY url_host (url_host)
)
SQL;
        $this->addSql($sql3);

        $sql4 = <<<SQL
CREATE TABLE content_metadata (
    id BIGINT AUTO_INCREMENT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    path VARCHAR(255) NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)
SQL;
        $this->addSql($sql4);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE IF EXISTS crawl_jobs');
        $this->addSql('DROP TABLE IF EXISTS crawl_job_tasks');
        $this->addSql('DROP TABLE IF EXISTS download_limits');
        $this->addSql('DROP TABLE IF EXISTS content_metadata');
    }
}
