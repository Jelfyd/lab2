<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20261126100000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Створив каталог космічних об’єктів, категорії та користувачів адміністратора';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE space_categories (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(80) NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX uniq_space_category_name ON space_categories (name)');
        $this->addSql('CREATE TABLE space_objects (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, category_id INTEGER NOT NULL, name VARCHAR(120) NOT NULL, description CLOB NOT NULL, diameter_km DOUBLE PRECISION DEFAULT NULL, distance_from_sun_million_km DOUBLE PRECISION DEFAULT NULL, fun_fact CLOB NOT NULL, CONSTRAINT FK_SPACE_OBJECT_CATEGORY FOREIGN KEY (category_id) REFERENCES space_categories (id) ON DELETE RESTRICT NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX idx_space_object_category ON space_objects (category_id)');
        $this->addSql('CREATE TABLE users (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
            , password VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX uniq_user_email ON users (email)');

        $this->addSql("INSERT INTO space_categories (id, name) VALUES (1, 'планета'), (2, 'супутник'), (3, 'космічний апарат')");
        $this->addSql("INSERT INTO space_objects (id, category_id, name, description, diameter_km, distance_from_sun_million_km, fun_fact) VALUES
            (1, 1, 'Земля', 'Єдина відома планета, на якій існує життя.', 12742, 149.6, 'Приблизно 71% поверхні Землі вкрито водою.'),
            (2, 1, 'Марс', 'Четверта планета від Сонця, відома як Червона планета.', 6779, 227.9, 'На Марсі розташований вулкан Олімп — найбільший відомий вулкан Сонячної системи.'),
            (3, 2, 'Європа', 'Крижаний супутник Юпітера, під поверхнею якого може бути океан.', 3122, 778.5, 'Океан під крижаною корою Європи може містити більше води, ніж усі океани Землі.'),
            (4, 1, 'Сатурн', 'Газовий гігант із виразною системою кілець.', 116460, 1433.5, 'Середня густина Сатурна менша за густину води.'),
            (5, 3, 'Вояджер-1', 'Автоматична міжпланетна станція NASA, запущена у 1977 році.', NULL, NULL, 'Вояджер-1 став першим апаратом, що увійшов у міжзоряний простір.')");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE space_objects');
        $this->addSql('DROP TABLE space_categories');
    }
}
