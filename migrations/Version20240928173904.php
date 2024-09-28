<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240928173904 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE profil_favoris (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT NOT NULL, utilisateur_favoris_id INT NOT NULL, INDEX IDX_C270B63FB88E14F (utilisateur_id), INDEX IDX_C270B636350450D (utilisateur_favoris_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE profil_favoris ADD CONSTRAINT FK_C270B63FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE profil_favoris ADD CONSTRAINT FK_C270B636350450D FOREIGN KEY (utilisateur_favoris_id) REFERENCES utilisateur (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE profil_favoris DROP FOREIGN KEY FK_C270B63FB88E14F');
        $this->addSql('ALTER TABLE profil_favoris DROP FOREIGN KEY FK_C270B636350450D');
        $this->addSql('DROP TABLE profil_favoris');
    }
}
