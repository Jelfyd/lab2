<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20261126130500 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Додав п’ять нових космічних об’єктів до каталогу';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("INSERT INTO space_objects (category_id, name, description, diameter_km, distance_from_sun_million_km, fun_fact) VALUES
            (1, 'Юпітер', 'Найбільша планета Сонячної системи, газовий гігант із численними супутниками.', 139820, 778.5, 'Велика червона пляма Юпітера — гігантський шторм, який спостерігають уже кілька століть.'),
            (1, 'Нептун', 'Крижаний гігант і восьма планета від Сонця.', 49244, 4495.1, 'Нептун відкрили у 1846 році за математичними розрахунками його впливу на орбіту Урана.'),
            (2, 'Місяць', 'Єдиний природний супутник Землі та найближче до нас небесне тіло.', 3474.8, 149.6, 'Місяць віддаляється від Землі приблизно на 3,8 сантиметра щороку.'),
            (2, 'Титан', 'Найбільший супутник Сатурна з густою атмосферою та озерами рідких вуглеводнів.', 5149.5, 1433.5, 'Титан — єдиний відомий супутник із щільною атмосферою.'),
            (3, 'Вояджер-2', 'Космічний апарат NASA, що дослідив Юпітер, Сатурн, Уран і Нептун.', NULL, NULL, 'Вояджер-2 — єдиний апарат, який зблизька дослідив Уран і Нептун.')");
    }

    public function down(Schema $schema): void
    {
        $this->addSql("DELETE FROM space_objects WHERE name IN ('Юпітер', 'Нептун', 'Місяць', 'Титан', 'Вояджер-2')");
    }
}
