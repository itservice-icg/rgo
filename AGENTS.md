# AGENTS.md

Guidance for AI coding agents working in this repository.

## Project Overview

This is the RGO web application, a Laravel 8 project for managing chemical/product registrations, imports, production registrations, companies, users, roles, permissions, and dashboard reporting.

Main stack:

- PHP `^7.3|^8.0`
- Laravel `8.x`
- Laravel Breeze authentication
- Spatie Laravel Permission for roles and permissions
- Maatwebsite Excel for spreadsheet imports
- Blade views in `resources/views`
- Laravel Mix, Tailwind CSS 2, Alpine.js, and SweetAlert2 for frontend assets

## Repository Layout

- `app/Http/Controllers`: Laravel controllers
- `app/Models`: Eloquent models
- `database/migrations`: schema migrations
- `resources/views`: Blade templates
- `resources/js` and `resources/css`: frontend source assets
- `routes/web.php`: main web routes
- `tests`: PHPUnit tests
- `public`: compiled/public assets
- `storage`: runtime files; avoid committing generated uploads, logs, cache, or local-only files

## Common Commands

Use these commands from the repository root.

```bash
composer install
npm install
php artisan migrate
php artisan test
npm run dev
npm run watch
npm run prod
```

Useful Laravel maintenance commands:

```bash
php artisan route:list
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan optimize:clear
```

There is also a Windows helper script:

```bat
run-clear-cache.bat
```

## Coding Guidelines

- Follow existing Laravel 8 patterns in the surrounding code.
- Keep controllers thin where practical; place reusable behavior in models, services, requests, policies, or helpers only when it clearly reduces duplication.
- Prefer Eloquent relationships and query builder APIs over raw SQL unless raw SQL is necessary.
- Validate request data before create/update operations.
- Preserve existing route names, URL structures, and view variable names unless the task explicitly requires a breaking change.
- For permissions, respect the existing Spatie role/permission model and middleware patterns.
- For file uploads, use Laravel storage APIs and keep public/private storage behavior consistent with nearby code.
- For imports/exports, prefer Maatwebsite Excel conventions already used in the project.

## Blade and Frontend Guidelines

- Keep Blade markup consistent with existing layouts in `resources/views/layouts`.
- Reuse existing partials/components when available.
- Keep Tailwind CSS usage compatible with Tailwind 2.
- Do not introduce a new frontend framework unless explicitly requested.
- If changing compiled assets in `public`, make sure they correspond to source changes and the build command used.
- Avoid broad visual redesigns when the request is a focused behavior or data change.

## Database and Migrations

- Add new migrations instead of editing migrations that may already have run, unless the user explicitly asks for a local-only rewrite.
- Use clear, reversible migrations where possible.
- Keep column names aligned with existing naming conventions.
- Be careful with destructive schema or data changes; call out risks before running them.

## Testing and Verification

When practical, verify changes with the narrowest useful command first:

```bash
php artisan test
```

For frontend asset changes:

```bash
npm run dev
```

If the full suite is not feasible, run targeted tests or at least validate relevant routes/views manually. Mention any tests that were not run.

## Working Tree Safety

- The repository may contain user changes. Do not revert, overwrite, or clean up unrelated files.
- Before editing, inspect the relevant files and preserve nearby user work.
- Avoid destructive commands such as `git reset --hard`, `git checkout --`, or recursive deletes unless the user explicitly requests them.
- Keep changes scoped to the user's request.

## Environment Notes

- Do not commit `.env` or local secrets.
- Prefer `.env.example` for documenting environment variables.
- Network access or dependency installation may require user approval in restricted environments.
- This repository may contain both Git and SVN metadata; do not modify VCS metadata unless explicitly asked.
