<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240928080747 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $roleAdmin = '["ROLE_USER", "ROLE_ADMIN"]';
        $roleUser  = '["ROLE_USER"]';
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, login VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, num_telephone INT DEFAULT NULL, updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', visible TINYINT(1) NOT NULL, connected_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', nom_photo_profil LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_LOGIN (login), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql("INSERT INTO annuaire.utilisateur (id, login, roles, password, email, code, num_telephone, updated_at, visible, connected_at, nom_photo_profil) VALUES (1, 'admin', '$roleAdmin', '".'$2y$13$HN37D9/DJ4Ttpd/lbDkWcOi6.hkTwa4f3sN.Ms0wB1QHWKgkjwPfe'."', 'admin@annuaire.com', 'admin', null, '2024-09-28 08:09:30', 1, null, null);");
        $this->addSql("INSERT INTO annuaire.utilisateur (id, login, roles, password, email, code, num_telephone, updated_at, visible, connected_at, nom_photo_profil) VALUES (2, 'trouchex', '$roleUser', '".'$2y$13$E3Hp60hOBvEk.DtpM71Nw.mJwMG4rjw7SaRzzKbfxrVUHyElZ5/36'."', 'xavier.trouche@etu.umontpellier.fr', 'trouchex', null, '2024-09-28 08:10:33', 1, null, null);");
        $this->addSql("INSERT INTO annuaire.utilisateur (id, login, roles, password, email, code, num_telephone, updated_at, visible, connected_at, nom_photo_profil) VALUES (3, 'achourl', '$roleUser', '".'$2y$13$U8dqvkEXj6pqCxza.bXtPuKeKYE/kRLbDnNO0aKff2p5zI7E1oDIq'."', 'lisa.achour@etu.umontpellier.fr', 'achourl', null, '2024-09-28 08:10:54', 1, null, null);");
        $this->addSql("INSERT INTO annuaire.utilisateur (id, login, roles, password, email, code, num_telephone, updated_at, visible, connected_at, nom_photo_profil) VALUES (4, 'invisible', '$roleUser', '".'$2y$13$QXr53eeRRtVlyWqjGaBnK.yVOqxyIJBoM987UelkPvO7qPo5SAGI2'."', 'homme@invisible.com', 'invisible', null, '2024-09-28 08:11:16', 0, null, null);");
        $this->addSql("INSERT INTO annuaire.utilisateur (id, login, roles, password, email, code, num_telephone, updated_at, visible, connected_at, nom_photo_profil) VALUES (5, 'velteri', '$roleUser', '".'$2y$13$jDPK0NgbcfA/fcbxw7ztLufB0l30vcu1eSlt6a15nREoruBiBSE8i'."', 'ilan.velter@etu.umontpellier.fr', 'velteri', null, '2024-09-28 08:11:37', 1, null, null);");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
