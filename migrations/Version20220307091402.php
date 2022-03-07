<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220307091402 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_3BAE0AA77E3C61F9');
        $this->addSql('DROP INDEX IDX_3BAE0AA764D218E');
        $this->addSql('DROP INDEX IDX_3BAE0AA75D83CC1');
        $this->addSql('CREATE TEMPORARY TABLE __temp__event AS SELECT id, state_id, location_id, owner_id, name, begin_at, limit_inscription_at, inscription_max, description, photo, is_display, is_active, duration FROM event');
        $this->addSql('DROP TABLE event');
        $this->addSql('CREATE TABLE event (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, state_id INTEGER NOT NULL, location_id INTEGER DEFAULT NULL, owner_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, begin_at DATETIME NOT NULL, limit_inscription_at DATETIME NOT NULL, inscription_max INTEGER NOT NULL, description VARCHAR(255) DEFAULT NULL, photo VARCHAR(255) DEFAULT NULL, is_display INTEGER NOT NULL, is_active BOOLEAN NOT NULL, duration INTEGER NOT NULL, CONSTRAINT FK_3BAE0AA75D83CC1 FOREIGN KEY (state_id) REFERENCES state (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_3BAE0AA764D218E FOREIGN KEY (location_id) REFERENCES location (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_3BAE0AA77E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO event (id, state_id, location_id, owner_id, name, begin_at, limit_inscription_at, inscription_max, description, photo, is_display, is_active, duration) SELECT id, state_id, location_id, owner_id, name, begin_at, limit_inscription_at, inscription_max, description, photo, is_display, is_active, duration FROM __temp__event');
        $this->addSql('DROP TABLE __temp__event');
        $this->addSql('CREATE INDEX IDX_3BAE0AA77E3C61F9 ON event (owner_id)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA764D218E ON event (location_id)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA75D83CC1 ON event (state_id)');
        $this->addSql('DROP INDEX IDX_92589AE2A76ED395');
        $this->addSql('DROP INDEX IDX_92589AE271F7E88B');
        $this->addSql('CREATE TEMPORARY TABLE __temp__event_user AS SELECT event_id, user_id FROM event_user');
        $this->addSql('DROP TABLE event_user');
        $this->addSql('CREATE TABLE event_user (event_id INTEGER NOT NULL, user_id INTEGER NOT NULL, PRIMARY KEY(event_id, user_id), CONSTRAINT FK_92589AE271F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_92589AE2A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO event_user (event_id, user_id) SELECT event_id, user_id FROM __temp__event_user');
        $this->addSql('DROP TABLE __temp__event_user');
        $this->addSql('CREATE INDEX IDX_92589AE2A76ED395 ON event_user (user_id)');
        $this->addSql('CREATE INDEX IDX_92589AE271F7E88B ON event_user (event_id)');
        $this->addSql('DROP INDEX IDX_6DC044C57E3C61F9');
        $this->addSql('CREATE TEMPORARY TABLE __temp__group AS SELECT id, owner_id, name FROM "group"');
        $this->addSql('DROP TABLE "group"');
        $this->addSql('CREATE TABLE "group" (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, owner_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, CONSTRAINT FK_6DC044C57E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO "group" (id, owner_id, name) SELECT id, owner_id, name FROM __temp__group');
        $this->addSql('DROP TABLE __temp__group');
        $this->addSql('CREATE INDEX IDX_6DC044C57E3C61F9 ON "group" (owner_id)');
        $this->addSql('DROP INDEX IDX_A4C98D39A76ED395');
        $this->addSql('DROP INDEX IDX_A4C98D39FE54D947');
        $this->addSql('CREATE TEMPORARY TABLE __temp__group_user AS SELECT group_id, user_id FROM group_user');
        $this->addSql('DROP TABLE group_user');
        $this->addSql('CREATE TABLE group_user (group_id INTEGER NOT NULL, user_id INTEGER NOT NULL, PRIMARY KEY(group_id, user_id), CONSTRAINT FK_A4C98D39FE54D947 FOREIGN KEY (group_id) REFERENCES "group" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_A4C98D39A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO group_user (group_id, user_id) SELECT group_id, user_id FROM __temp__group_user');
        $this->addSql('DROP TABLE __temp__group_user');
        $this->addSql('CREATE INDEX IDX_A4C98D39A76ED395 ON group_user (user_id)');
        $this->addSql('CREATE INDEX IDX_A4C98D39FE54D947 ON group_user (group_id)');
        $this->addSql('DROP INDEX IDX_5E9E89CB8BAC62AF');
        $this->addSql('CREATE TEMPORARY TABLE __temp__location AS SELECT id, city_id, name, adress, latitude, longitude, is_active FROM location');
        $this->addSql('DROP TABLE location');
        $this->addSql('CREATE TABLE location (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, city_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, adress VARCHAR(255) NOT NULL, latitude VARCHAR(255) NOT NULL, longitude VARCHAR(255) NOT NULL, is_active BOOLEAN DEFAULT 1 NOT NULL, CONSTRAINT FK_5E9E89CB8BAC62AF FOREIGN KEY (city_id) REFERENCES city (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO location (id, city_id, name, adress, latitude, longitude, is_active) SELECT id, city_id, name, adress, latitude, longitude, is_active FROM __temp__location');
        $this->addSql('DROP TABLE __temp__location');
        $this->addSql('CREATE INDEX IDX_5E9E89CB8BAC62AF ON location (city_id)');
        $this->addSql('DROP INDEX IDX_8D93D649F6BD1646');
        $this->addSql('DROP INDEX UNIQ_8D93D64986CC499D');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, site_id, pseudo, roles, password, name, surname, tel, mail, is_active, photo FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, site_id INTEGER NOT NULL, pseudo VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, surname VARCHAR(255) NOT NULL, tel VARCHAR(255) NOT NULL, mail VARCHAR(255) NOT NULL, is_active BOOLEAN DEFAULT 1 NOT NULL, photo VARCHAR(255) DEFAULT NULL, CONSTRAINT FK_8D93D649F6BD1646 FOREIGN KEY (site_id) REFERENCES site (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO user (id, site_id, pseudo, roles, password, name, surname, tel, mail, is_active, photo) SELECT id, site_id, pseudo, roles, password, name, surname, tel, mail, is_active, photo FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE INDEX IDX_8D93D649F6BD1646 ON user (site_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64986CC499D ON user (pseudo)');
        $this->addSql('DROP INDEX IDX_D96CF1FF71F7E88B');
        $this->addSql('DROP INDEX IDX_D96CF1FFA76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user_event AS SELECT user_id, event_id FROM user_event');
        $this->addSql('DROP TABLE user_event');
        $this->addSql('CREATE TABLE user_event (user_id INTEGER NOT NULL, event_id INTEGER NOT NULL, PRIMARY KEY(user_id, event_id), CONSTRAINT FK_D96CF1FFA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_D96CF1FF71F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO user_event (user_id, event_id) SELECT user_id, event_id FROM __temp__user_event');
        $this->addSql('DROP TABLE __temp__user_event');
        $this->addSql('CREATE INDEX IDX_D96CF1FF71F7E88B ON user_event (event_id)');
        $this->addSql('CREATE INDEX IDX_D96CF1FFA76ED395 ON user_event (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_3BAE0AA75D83CC1');
        $this->addSql('DROP INDEX IDX_3BAE0AA764D218E');
        $this->addSql('DROP INDEX IDX_3BAE0AA77E3C61F9');
        $this->addSql('CREATE TEMPORARY TABLE __temp__event AS SELECT id, state_id, location_id, owner_id, name, begin_at, limit_inscription_at, duration, inscription_max, description, photo, is_display, is_active FROM event');
        $this->addSql('DROP TABLE event');
        $this->addSql('CREATE TABLE event (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, state_id INTEGER NOT NULL, location_id INTEGER DEFAULT NULL, owner_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, begin_at DATETIME NOT NULL, limit_inscription_at DATETIME NOT NULL, duration INTEGER NOT NULL, inscription_max INTEGER NOT NULL, description VARCHAR(255) DEFAULT NULL, photo VARCHAR(255) DEFAULT NULL, is_display INTEGER NOT NULL, is_active BOOLEAN NOT NULL)');
        $this->addSql('INSERT INTO event (id, state_id, location_id, owner_id, name, begin_at, limit_inscription_at, duration, inscription_max, description, photo, is_display, is_active) SELECT id, state_id, location_id, owner_id, name, begin_at, limit_inscription_at, duration, inscription_max, description, photo, is_display, is_active FROM __temp__event');
        $this->addSql('DROP TABLE __temp__event');
        $this->addSql('CREATE INDEX IDX_3BAE0AA75D83CC1 ON event (state_id)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA764D218E ON event (location_id)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA77E3C61F9 ON event (owner_id)');
        $this->addSql('DROP INDEX IDX_92589AE271F7E88B');
        $this->addSql('DROP INDEX IDX_92589AE2A76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__event_user AS SELECT event_id, user_id FROM event_user');
        $this->addSql('DROP TABLE event_user');
        $this->addSql('CREATE TABLE event_user (event_id INTEGER NOT NULL, user_id INTEGER NOT NULL, PRIMARY KEY(event_id, user_id))');
        $this->addSql('INSERT INTO event_user (event_id, user_id) SELECT event_id, user_id FROM __temp__event_user');
        $this->addSql('DROP TABLE __temp__event_user');
        $this->addSql('CREATE INDEX IDX_92589AE271F7E88B ON event_user (event_id)');
        $this->addSql('CREATE INDEX IDX_92589AE2A76ED395 ON event_user (user_id)');
        $this->addSql('DROP INDEX IDX_6DC044C57E3C61F9');
        $this->addSql('CREATE TEMPORARY TABLE __temp__group AS SELECT id, owner_id, name FROM "group"');
        $this->addSql('DROP TABLE "group"');
        $this->addSql('CREATE TABLE "group" (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, owner_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO "group" (id, owner_id, name) SELECT id, owner_id, name FROM __temp__group');
        $this->addSql('DROP TABLE __temp__group');
        $this->addSql('CREATE INDEX IDX_6DC044C57E3C61F9 ON "group" (owner_id)');
        $this->addSql('DROP INDEX IDX_A4C98D39FE54D947');
        $this->addSql('DROP INDEX IDX_A4C98D39A76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__group_user AS SELECT group_id, user_id FROM group_user');
        $this->addSql('DROP TABLE group_user');
        $this->addSql('CREATE TABLE group_user (group_id INTEGER NOT NULL, user_id INTEGER NOT NULL, PRIMARY KEY(group_id, user_id))');
        $this->addSql('INSERT INTO group_user (group_id, user_id) SELECT group_id, user_id FROM __temp__group_user');
        $this->addSql('DROP TABLE __temp__group_user');
        $this->addSql('CREATE INDEX IDX_A4C98D39FE54D947 ON group_user (group_id)');
        $this->addSql('CREATE INDEX IDX_A4C98D39A76ED395 ON group_user (user_id)');
        $this->addSql('DROP INDEX IDX_5E9E89CB8BAC62AF');
        $this->addSql('CREATE TEMPORARY TABLE __temp__location AS SELECT id, city_id, name, adress, latitude, longitude, is_active FROM location');
        $this->addSql('DROP TABLE location');
        $this->addSql('CREATE TABLE location (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, city_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, adress VARCHAR(255) NOT NULL, latitude VARCHAR(255) NOT NULL, longitude VARCHAR(255) NOT NULL, is_active BOOLEAN DEFAULT 1 NOT NULL)');
        $this->addSql('INSERT INTO location (id, city_id, name, adress, latitude, longitude, is_active) SELECT id, city_id, name, adress, latitude, longitude, is_active FROM __temp__location');
        $this->addSql('DROP TABLE __temp__location');
        $this->addSql('CREATE INDEX IDX_5E9E89CB8BAC62AF ON location (city_id)');
        $this->addSql('DROP INDEX UNIQ_8D93D64986CC499D');
        $this->addSql('DROP INDEX IDX_8D93D649F6BD1646');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, site_id, pseudo, roles, password, name, surname, tel, mail, is_active, photo FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, site_id INTEGER NOT NULL, pseudo VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, surname VARCHAR(255) NOT NULL, tel VARCHAR(255) NOT NULL, mail VARCHAR(255) NOT NULL, is_active BOOLEAN DEFAULT 1 NOT NULL, photo VARCHAR(255) DEFAULT NULL)');
        $this->addSql('INSERT INTO user (id, site_id, pseudo, roles, password, name, surname, tel, mail, is_active, photo) SELECT id, site_id, pseudo, roles, password, name, surname, tel, mail, is_active, photo FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64986CC499D ON user (pseudo)');
        $this->addSql('CREATE INDEX IDX_8D93D649F6BD1646 ON user (site_id)');
        $this->addSql('DROP INDEX IDX_D96CF1FFA76ED395');
        $this->addSql('DROP INDEX IDX_D96CF1FF71F7E88B');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user_event AS SELECT user_id, event_id FROM user_event');
        $this->addSql('DROP TABLE user_event');
        $this->addSql('CREATE TABLE user_event (user_id INTEGER NOT NULL, event_id INTEGER NOT NULL, PRIMARY KEY(user_id, event_id))');
        $this->addSql('INSERT INTO user_event (user_id, event_id) SELECT user_id, event_id FROM __temp__user_event');
        $this->addSql('DROP TABLE __temp__user_event');
        $this->addSql('CREATE INDEX IDX_D96CF1FFA76ED395 ON user_event (user_id)');
        $this->addSql('CREATE INDEX IDX_D96CF1FF71F7E88B ON user_event (event_id)');
    }
}
