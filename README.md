# Tourist Guide — نظام الدليل السياحي

نظام متكامل لإدارة الوجهات السياحية والأنشطة، مع حجز ذكي، تخطيط رحلات، نقاط ولاء، ولوحة تحكم إدارية.

---

## Quick Start

### Requirements

- PHP >= 8.1
- Composer
- MySQL / MariaDB
- Node.js & npm

### Setup

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
```

Configure your database in `.env`:

```env
DB_DATABASE=tourist_guide
DB_USERNAME=root
DB_PASSWORD=
```

```bash
# Create the database first, then:
php artisan migrate
php artisan db:seed
php artisan storage:link
npm run build
php artisan serve
```

Visit **http://127.0.0.1:8000**

### Default admin (development only)

| | |
|---|---|
| URL | `/admin/login` |
| Email | `admin@tourist.com` |
| Password | `password` |

> **Security:** Never use the default admin credentials in production. Set `ADMIN_EMAIL` and `ADMIN_PASSWORD` in `.env` before running seeders, and keep `APP_DEBUG=false`.

### Optional environment variables

| Variable | Purpose |
|---|---|
| `OPENWEATHER_API_KEY` | Weather data for destinations |
| `GOOGLE_CLIENT_ID` / `GOOGLE_CLIENT_SECRET` | Google social login |
| `FACEBOOK_CLIENT_ID` / `FACEBOOK_CLIENT_SECRET` | Facebook social login |
| `PROVIDER_COMMISSION_PERCENT` | Provider earnings share (default: 70) |

See `.env.example` for the full list.

---

## Features

- **Smart booking** — activity reservations with status tracking and coupon validation
- **Trip planner** — day-by-day itineraries with sharing support
- **Loyalty & badges** — points, membership tiers, and achievement badges
- **Coupons** — percentage or fixed discounts with usage limits
- **Gallery** — multi-image uploads for destinations and activities
- **Interactive map** — GPS-based destination map (Leaflet / OpenStreetMap)
- **Weather** — live weather via OpenWeatherMap (optional)
- **Social login** — Google & Facebook via Laravel Socialite (optional)
- **Content providers** — provider dashboard, storefront, and earnings
- **Bilingual** — Arabic & English content support
- **Admin panel** — full CRUD for destinations, activities, bookings, and more

---

## Tech Stack

- Laravel 10
- MySQL
- Blade templates
- Vite
- Laravel Sanctum
- Laravel Socialite

---

## Documentation

Detailed guides are in the [`docs/`](docs/) folder:

| Guide | Description |
|---|---|
| [QUICK_START.md](docs/QUICK_START.md) | 5-minute setup guide |
| [INSTALLATION_GUIDE.md](docs/INSTALLATION_GUIDE.md) | Full installation steps |
| [API_DOCUMENTATION.md](docs/API_DOCUMENTATION.md) | API routes reference |
| [DATABASE_SCHEMA.md](docs/DATABASE_SCHEMA.md) | Database structure |
| [GPS_FEATURE.md](docs/GPS_FEATURE.md) | Interactive map feature |
| [WEATHER_FEATURE.md](docs/WEATHER_FEATURE.md) | Weather integration |
| [USER_GUIDE.md](docs/USER_GUIDE.md) | End-user guide |
| [ADMIN_EDITING_GUIDE.md](docs/ADMIN_EDITING_GUIDE.md) | Admin panel guide |

---

## Running tests

```bash
php artisan test
```

---

## Production checklist

- Set `APP_ENV=production` and `APP_DEBUG=false`
- Generate a new `APP_KEY` (`php artisan key:generate`)
- Change `ADMIN_EMAIL` / `ADMIN_PASSWORD` before seeding
- Run `npm run build` and `php artisan config:cache`
- Update OAuth redirect URIs to your production domain
- **Never commit `.env`** — only `.env.example`

---

## License

This project is open-sourced under the [MIT License](LICENSE).
