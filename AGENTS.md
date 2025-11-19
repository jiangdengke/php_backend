# Repository Guidelines

## Project Structure & Module Organization

- Backend is a Laravel application.
- Application code: `app/` (HTTP controllers, models, providers).
- Routes: `routes/web.php` for web, `routes/api.php` for JSON APIs.
- Database: `database/migrations/` for schema, `database/seeders/` for seed data, `database/database.sqlite` for local dev.
- Views & frontend assets (if used): `resources/`.
- Tests: `tests/Unit/` and `tests/Feature/`.

## Build, Test, and Development Commands

- Install PHP dependencies: `composer install`.
- Run database migrations (SQLite): `php artisan migrate`.
- Start local server: `php artisan serve` (default on `http://127.0.0.1:8000`).
- Run tests: `php artisan test` (wrapper around PHPUnit).
- Full dev environment (optional, uses npm & queue & logs): `composer dev`.

## Coding Style & Naming Conventions

- Follow PSR-12 and Laravel conventions.
- PHP: 4 spaces for indentation, no tabs.
- Classes use StudlyCase, methods and variables use camelCase, database columns use snake_case.
- Keep controllers thin; move business logic into models/services when it grows.
- Format PHP code with Laravel Pint: `./vendor/bin/pint`.

## Testing Guidelines

- Use PHPUnit via `php artisan test`.
- Place feature tests in `tests/Feature/`, unit tests in `tests/Unit/`.
- Test files end with `Test.php` (e.g., `UserApiTest.php`).
- For new features, add at least basic happy-path and main error-path tests.

## Commit & Pull Request Guidelines

- Write clear, imperative commit messages (e.g., "Add course enrollment API").
- Keep changes focused and small; separate refactors from feature changes when possible.
- For pull requests, include:
- A short summary of the change and motivation.
- Notes on breaking changes or migration impacts.
- How to reproduce and how you tested (commands, scenarios).

## Agent-Specific Instructions

- Prefer minimal, targeted changes that respect existing structure.
- Do not commit or depend on `.env` or other secrets; use configuration and env vars.
- When modifying database-related code, favor migrations over manual SQL or editing the SQLite file.

