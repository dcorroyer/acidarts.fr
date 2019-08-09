<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190808092412 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EE763C10B2');
        $this->addSql('DROP INDEX IDX_2FB3D0EE763C10B2 ON project');
        $this->addSql('ALTER TABLE project DROP videos_id');
        $this->addSql('ALTER TABLE video ADD project_id INT DEFAULT NULL, CHANGE videoname videoname VARCHAR(191) NOT NULL');
        $this->addSql('ALTER TABLE video ADD CONSTRAINT FK_7CC7DA2C166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('CREATE INDEX IDX_7CC7DA2C166D1F9C ON video (project_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE project ADD videos_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE763C10B2 FOREIGN KEY (videos_id) REFERENCES video (id)');
        $this->addSql('CREATE INDEX IDX_2FB3D0EE763C10B2 ON project (videos_id)');
        $this->addSql('ALTER TABLE video DROP FOREIGN KEY FK_7CC7DA2C166D1F9C');
        $this->addSql('DROP INDEX IDX_7CC7DA2C166D1F9C ON video');
        $this->addSql('ALTER TABLE video DROP project_id, CHANGE videoname videoname VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
    }
}
