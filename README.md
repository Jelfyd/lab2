# Каталог космічних об’єктів

Навчальний вебсервіс на Symfony: каталог і сторінки космічних об’єктів, JSON API,
SQLite/Doctrine, EasyAdmin та Python-клієнт.

## Вимоги

- PHP 8.2 або новіший з розширеннями `ctype`, `iconv`, `mbstring` та `pdo_sqlite`;
- Composer.

## Запуск

З кореня проєкту виконайте:

```bash
composer install
php bin/console doctrine:migrations:migrate
php bin/console app:admin:create
symfony serve
```

Під час `composer install` ассети бандлів автоматично копіюються до `public/bundles`.
Якщо потрібно повторити вручну, виконайте `php bin/console assets:install public`.

Команда створення адміністратора запитує email і двічі пароль; введення пароля
приховано. Пароль не зберігається у вихідному коді. Вхід доступний на
`/login`, адмін-панель — на `/admin`.

Поточна SQLite-база з’явиться у `var/data.db`. Doctrine-міграції створюють
таблиці категорій, космічних об’єктів і користувачів та додають демонстраційні
записи каталогу.

## Сторінки та API

- `/` — каталог із пошуком без урахування регістру, фільтром категорій і щоденним «Об’єктом дня»;
- `/space/{id}` — сторінка одного космічного об’єкта;
- `/api/space` — JSON-список;
- `/api/space/{id}` — один запис або HTTP 404;
- `/admin` — захищена CRUD-адмінка категорій та об’єктів.

Приклад Python-клієнта:

```bash
python client/client.py
python client/client.py --id 1
```

За потреби можна задати URL сервера через `--base-url`.
