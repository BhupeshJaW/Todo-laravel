[![Open in Visual Studio Code](https://classroom.github.com/assets/open-in-vscode-2e0aaae1b6195c2367325f4f02e2d04e9abb55f0b24a779b69b11b9e10269abc.svg)](https://classroom.github.com/online_ide?assignment_repo_id=21305109&assignment_repo_type=AssignmentRepo)
Tadieu
======

This is Tadieu, a todo app.

Installation
------------

This assumes you have Laravel Herd setup.

1. Clone this repository
2. Install dependencies: `composer install`
3. Copy default env and generate key: `cp .env.example .env && php artisan key:generate`
4. Run migrations: `php artisan migrate`
5. Seed the databse: `php artisan db:seed`
6. Install NodeJS dependencies: `npm ci`

Development
-----------

Start the dev server with `npm run dev` and open `http://tadieu.test`.
