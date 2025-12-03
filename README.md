# LifeQuest

> 📺 **Demo:** https://lifequest.ntu195.vpsttt.vn

LifeQuest is a habit + challenge companion built with Laravel 12, Livewire 3, and Tailwind CSS. This README documents how to bootstrap the project locally, how to build for production, and what the repository structure looks like.

## Tech Stack

- PHP 8.2 + Laravel 12
- Livewire 3 & Volt components
- Tailwind CSS + Vite (ESBuild) for asset bundling
- MySQL 8 (or compatible) as the primary datastore
- Redis / Pusher-compatible broadcaster (optional, for realtime notifications)

## Prerequisites

- PHP 8.2 with required extensions (`bcmath`, `ctype`, `fileinfo`, `json`, `mbstring`, `openssl`, `pdo_mysql`, `tokenizer`)
- Composer 2.6+
- Node.js 18+ and npm 9+
- MySQL 8+ (or MariaDB 10.6+) running locally
- Redis (optional, only if you enable queues/broadcasting)

## Quick Start

```bash
git clone https://github.com/PP2534/LifeQuest.git lifequest
cd lifequest

cp .env.example .env                # configure DB, APP_URL, mail, Pusher...
composer install
npm install

php artisan key:generate
php artisan storage:link

# configure database credentials in .env, then
php artisan migrate --seed          # seeds are optional; drop --seed if not needed
```

## Running Locally

### Backend API + Laravel

```bash
php artisan serve                   # http://127.0.0.1:8000
```

Optionally run queues / websockets in separate terminals:

```bash
php artisan queue:listen
php artisan schedule:work
```

### Frontend Assets

```bash
npm run dev                         # Vite dev server with HMR
```

You can also run everything at once via Composer (uses concurrently under the hood):

```bash
composer run dev
```

## Building for Production

```bash
npm run build                       # Compile and version assets
php artisan optimize                # Cache config/routes/views
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Deploy the generated `public/build` assets along with the optimized Laravel cache files. Remember to run database migrations on the target environment as part of your release (`php artisan migrate --force`).

## Project Structure

```
app/                 # Domain logic, Livewire components, models, services
bootstrap/           # Framework bootstrap files, cache bootstrapper
config/              # Laravel + package configuration files
database/            # Migrations, seeders, factories
public/              # Web root (index.php, built assets, storage symlink)
resources/           # Blade views, Livewire Volt views, JS, CSS, Tailwind
routes/              # Route definitions (web, api, console, admin)
storage/             # App/runtime storage (logs, cache, compiled views)
tests/               # Feature & unit tests
```

Notable directories inside `app/`:

- `App/Livewire/**` — Livewire components for challenges, habits, profile, notifications
- `App/Models/**` — Eloquent models for habits, challenges, participants, logs
- `App/Notifications/**` — Database/mail notifications (habits, challenges, reminders)
- `App/Services/XpService.php` — Encapsulated XP reward rules

## Troubleshooting

- **Hot reload not updating:** delete `public/hot` and rerun `npm run dev`.
- **Stale caches after deploy:** run `php artisan optimize:clear` to flush config/route/view caches.
- **Storage permission issues:** ensure `storage/` and `bootstrap/cache/` are writable by the web server user.
- **Broadcasting failures:** verify Pusher (or Laravel WebSockets) credentials in `.env` and queue workers are running.

## License

This project inherits the default Laravel MIT license. Use, modify, and distribute under the terms of the MIT license.

