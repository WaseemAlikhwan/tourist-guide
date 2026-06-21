# Tourist Guide — نظام الدليل السياحي

نظام متكامل لإدارة الوجهات السياحية والأنشطة، مع حجز ذكي، تخطيط رحلات، ولوحة تحكم إدارية.

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



<img width="500" alt="Screenshot (8)" src="https://github.com/user-attachments/assets/6ca32ea2-6e9d-46d6-b005-47224c744e26" />

<img width="500"  alt="Screenshot (102)" src="https://github.com/user-attachments/assets/25856813-a852-444c-ac16-89a3f9f52953" />
<img width="500"  alt="Screenshot (101)" src="https://github.com/user-attachments/assets/742559af-8232-4b78-a039-04fc0ea6e482" />
<img width="500"  alt="Screenshot (100)" src="https://github.com/user-attachments/assets/f15473da-c912-407e-ae56-dc3232378d6c" />
<img width="500"  alt="Screenshot (99)" src="https://github.com/user-attachments/assets/defe534c-e7ff-45ba-a8f8-d4771d75a16a" />
<img width="500"  alt="Screenshot (98)" src="https://github.com/user-attachments/assets/5f44b926-f56b-482b-97c6-37d72b4b1167" />
<img width="500"  alt="Screenshot (97)" src="https://github.com/user-attachments/assets/ac60ddab-991f-4ffe-a0c8-02ada3271f52" />
<img width="500"  alt="Screenshot (96)" src="https://github.com/user-attachments/assets/04c4e890-59bf-4294-bb50-5f100fd8da77" />
<img width="500"  alt="Screenshot (95)" src="https://github.com/user-attachments/assets/9ef2d74d-f2c5-4911-844e-d8a9abb0e0aa" />
<img width="500"  alt="Screenshot (94)" src="https://github.com/user-attachments/assets/5a4536e9-cad1-475a-a3ad-c5e14892af62" />
<img width="500"  alt="Screenshot (93)" src="https://github.com/user-attachments/assets/b8165744-6545-4a6f-b285-958b374c15cd" />
<img width="500"  alt="Screenshot (92)" src="https://github.com/user-attachments/assets/0e4c772c-ec32-4c74-83b9-83aff4ee36d2" />
<img width="500"  alt="Screenshot (91)" src="https://github.com/user-attachments/assets/ccb49df8-da4b-42a5-aa01-b7fbe0308042" />
<img width="500"  alt="Screenshot (90)" src="https://github.com/user-attachments/assets/b0d615bd-925d-4853-a951-e42bafed8311" />
<img width="500"  alt="Screenshot (89)" src="https://github.com/user-attachments/assets/59673b8b-9513-438c-82a0-f310ba0f0289" />
<img width="500"  alt="Screenshot (88)" src="https://github.com/user-attachments/assets/0d28f078-8801-4273-9884-e24723c36c25" />
<img width="500"  alt="Screenshot (87)" src="https://github.com/user-attachments/assets/bddbeeb2-6870-4fa3-b003-4af8ab0f62e1" />
<img width="500"  alt="Screenshot (86)" src="https://github.com/user-attachments/assets/0ae75367-81e0-4916-95c4-874d9da566da" />
<img width="500"  alt="Screenshot (85)" src="https://github.com/user-attachments/assets/73ab71ed-57e3-4308-8182-c90b19f7336d" />
<img width="500"  alt="Screenshot (84)" src="https://github.com/user-attachments/assets/9a40ac3f-f8de-4e77-8237-40ed8cb6263b" />
<img width="500"  alt="Screenshot (83)" src="https://github.com/user-attachments/assets/68f5f8ec-da28-4772-b912-389b825a3ab0" />
<img width="500"  alt="Screenshot (82)" src="https://github.com/user-attachments/assets/37db2648-11a9-4de1-a0f4-d64084285a3d" />
<img width="500"  alt="Screenshot (81)" src="https://github.com/user-attachments/assets/9e2c90aa-8899-40ce-ac54-abcdd30d5c93" />
<img width="500"  alt="Screenshot (80)" src="https://github.com/user-attachments/assets/04a5361a-bff2-478a-8b72-081dbe8859cb" />
<img width="500"  alt="Screenshot (79)" src="https://github.com/user-attachments/assets/a2164711-089f-42ac-b12d-6c5b74a863d7" />
<img width="500"  alt="Screenshot (78)" src="https://github.com/user-attachments/assets/4efb8eac-64be-4952-945b-9b773b691911" />
<img width="500"  alt="Screenshot (77)" src="https://github.com/user-attachments/assets/c4378548-64a5-4179-b0d8-2a921a988383" />
<img width="500"  alt="Screenshot (76)" src="https://github.com/user-attachments/assets/13e60127-b634-48fe-ac91-025fa6fbd580" />
<img width="500"  alt="Screenshot (75)" src="https://github.com/user-attachments/assets/6af0c0ee-f748-4054-a38b-49802340a030" />
<img width="500"  alt="Screenshot (74)" src="https://github.com/user-attachments/assets/795e720a-f0f3-4c05-b3c6-b19a726fb8b0" />
<img width="500"  alt="Screenshot (73)" src="https://github.com/user-attachments/assets/b0abd200-9791-4ae7-85b6-5eedd17d21c8" />
<img width="500"  alt="Screenshot (72)" src="https://github.com/user-attachments/assets/f1e408dd-ec41-4509-afdc-72224503501f" />
<img width="500"  alt="Screenshot (71)" src="https://github.com/user-attachments/assets/f374a450-bba5-47e7-bd7d-5a51a73a2ae1" />
<img width="500"  alt="Screenshot (70)" src="https://github.com/user-attachments/assets/91d1b21e-a941-43ba-a0e3-6645376ce93a" />
<img width="500"  alt="Screenshot (69)" src="https://github.com/user-attachments/assets/5d2f1d83-a4e9-4267-8c41-f16d763b82e1" />
<img width="500"  alt="Screenshot (68)" src="https://github.com/user-attachments/assets/bfad8f0c-1f7b-4268-b240-b5cfb037915b" />
<img width="500"  alt="Screenshot (67)" src="https://github.com/user-attachments/assets/bc282dd7-e2bd-4fdf-8d5b-2beaa2f36b33" />
<img width="500"  alt="Screenshot (66)" src="https://github.com/user-attachments/assets/a71668c9-d014-4eff-b8fa-e7780d577411" />
<img width="500"  alt="Screenshot (65)" src="https://github.com/user-attachments/assets/c9577be4-4dfa-49f8-9c35-2eb02e78fb62" />
<img width="500"  alt="Screenshot (64)" src="https://github.com/user-attachments/assets/fa0297a3-90a9-4d99-90da-59e1de19802a" />
<img width="500"  alt="Screenshot (63)" src="https://github.com/user-attachments/assets/71a64293-1744-41d8-b931-1017a671ce36" />
<img width="500"  alt="Screenshot (62)" src="https://github.com/user-attachments/assets/0a3cbf78-0bac-4975-9285-17994c65644c" />
<img width="500"  alt="Screenshot (61)" src="https://github.com/user-attachments/assets/69837a8b-86bd-48ba-97a0-d30f3f19e098" />
<img width="500"  alt="Screenshot (60)" src="https://github.com/user-attachments/assets/8bb5e13c-df3f-40a4-8836-72020cbfac49" />
<img width="500"  alt="Screenshot (59)" src="https://github.com/user-attachments/assets/a6900542-eb56-4bfb-86da-0f6344ebcf46" />
<img width="500"  alt="Screenshot (58)" src="https://github.com/user-attachments/assets/32f7f6da-af6a-4f15-bdf3-30516779aea2" />
<img width="500"  alt="Screenshot (57)" src="https://github.com/user-attachments/assets/07886797-f734-4712-a32c-61dfd70dccf9" />
<img width="500"  alt="Screenshot (56)" src="https://github.com/user-attachments/assets/db6d30a0-eb53-481f-b271-0101d81a729d" />
<img width="500"  alt="Screenshot (55)" src="https://github.com/user-attachments/assets/45733994-34c1-44f2-85c8-b2ddc75b7fda" />
<img width="500"  alt="Screenshot (54)" src="https://github.com/user-attachments/assets/63ea4d7f-17a0-4f95-8a83-b8cafb9ab694" />
<img width="500"  alt="Screenshot (53)" src="https://github.com/user-attachments/assets/e1d330c8-86b4-41a1-811f-080dae9c17f1" />
<img width="500"  alt="Screenshot (52)" src="https://github.com/user-attachments/assets/2f65739e-2378-4745-8a66-bd3a6e545edc" />
<img width="500"  alt="Screenshot (51)" src="https://github.com/user-attachments/assets/3fee827f-860c-40de-b8e7-a4949f1efc00" />
<img width="500"  alt="Screenshot (50)" src="https://github.com/user-attachments/assets/b97083be-c95c-4094-8830-29d19c2bb121" />
<img width="500"  alt="Screenshot (49)" src="https://github.com/user-attachments/assets/06de6ecd-0ef4-4822-a064-e6dd680ca18c" />
<img width="500"  alt="Screenshot (48)" src="https://github.com/user-attachments/assets/051d1bc9-439a-488f-ab61-bbc93698424d" />
<img width="500"  alt="Screenshot (47)" src="https://github.com/user-attachments/assets/bb8c315f-5f0e-4d8c-9831-8eb64acca852" />
<img width="500"  alt="Screenshot (46)" src="https://github.com/user-attachments/assets/9ebfd0d6-b66b-4adb-bd89-1148d22e0242" />
<img width="500"  alt="Screenshot (45)" src="https://github.com/user-attachments/assets/1dd673d9-2fa4-47b4-865f-bde93bd52b95" />
<img width="500"  alt="Screenshot (44)" src="https://github.com/user-attachments/assets/6b9b0483-6052-42d0-9f13-a71ac65b863f" />
<img width="500"  alt="Screenshot (43)" src="https://github.com/user-attachments/assets/c58dc472-efce-46e7-8f5d-ea2208bc954b" />
<img width="500"  alt="Screenshot (42)" src="https://github.com/user-attachments/assets/a65a2476-ab9b-48e6-ac67-d565ef25d447" />
<img width="500"  alt="Screenshot (41)" src="https://github.com/user-attachments/assets/d70e5554-bfdd-4219-87f2-5406880690e0" />
<img width="500"  alt="Screenshot (40)" src="https://github.com/user-attachments/assets/39d22f6f-e191-49c0-b975-5e2c36781965" />
<img width="500"  alt="Screenshot (39)" src="https://github.com/user-attachments/assets/4923c16d-46b6-4e6e-87a9-4cc93c421bcb" />
<img width="500"  alt="Screenshot (38)" src="https://github.com/user-attachments/assets/22e11353-2bbf-4651-93fe-7dfd2e17c3ad" />
<img width="500"  alt="Screenshot (37)" src="https://github.com/user-attachments/assets/23932d83-8261-4e5f-bc0a-927eff1b0fb7" />
<img width="500"  alt="Screenshot (36)" src="https://github.com/user-attachments/assets/7bf9be7b-12a8-48c7-898a-f86bed6f285f" />
<img width="500"  alt="Screenshot (35)" src="https://github.com/user-attachments/assets/9f5e5929-b1e1-42a8-81ed-03668462a33e" />
<img width="500"  alt="Screenshot (34)" src="https://github.com/user-attachments/assets/1d7d5f8f-e7d8-45c3-8c65-e0e06fc24f81" />
<img width="500"  alt="Screenshot (33)" src="https://github.com/user-attachments/assets/3e8286b3-e4a0-42b7-8112-32d31ca5008e" />
<img width="500"  alt="Screenshot (32)" src="https://github.com/user-attachments/assets/ca1f0df9-7293-49e9-93cd-49b462019e07" />
<img width="500"  alt="Screenshot (31)" src="https://github.com/user-attachments/assets/8f76ebbb-3d32-411a-ad6b-67bda89278d9" />
<img width="500"  alt="Screenshot (30)" src="https://github.com/user-attachments/assets/d31cd40b-0fc9-4092-8344-326aa53841b0" />
<img width="500"  alt="Screenshot (29)" src="https://github.com/user-attachments/assets/8e64ff84-ca35-4df6-a5cd-e7a21bf68881" />
<img width="500"  alt="Screenshot (28)" src="https://github.com/user-attachments/assets/c9066fe7-4d7a-4ef3-a910-ed21461b194f" />
<img width="500"  alt="Screenshot (27)" src="https://github.com/user-attachments/assets/a8b8b8f2-6b77-4546-9dd4-6e002ffeefcb" />
<img width="500"  alt="Screenshot (26)" src="https://github.com/user-attachments/assets/3e468116-d819-4fd0-9264-bd6ce573001f" />
<img width="500"  alt="Screenshot (25)" src="https://github.com/user-attachments/assets/b26d7e3f-7ed3-4b7c-ab0e-18d776095d5c" />
<img width="500"  alt="Screenshot (24)" src="https://github.com/user-attachments/assets/96e76796-79b5-45f4-bf43-6971983166af" />
<img width="500"  alt="Screenshot (23)" src="https://github.com/user-attachments/assets/e1a4c96d-3ec4-420c-8357-34981c8617fd" />
<img width="500"  alt="Screenshot (22)" src="https://github.com/user-attachments/assets/b7fae050-6b42-44ae-a641-9e91cfc29248" />
<img width="500"  alt="Screenshot (21)" src="https://github.com/user-attachments/assets/ee10c76c-1399-4199-b21c-bf5867708bf4" />
<img width="500"  alt="Screenshot (20)" src="https://github.com/user-attachments/assets/24fd6280-c742-4547-ae2d-86c05f1f7c62" />
<img width="500"  alt="Screenshot (19)" src="https://github.com/user-attachments/assets/edb7f556-2af2-4ebc-bbe2-10f403f1a097" />
<img width="500"  alt="Screenshot (18)" src="https://github.com/user-attachments/assets/d8c076aa-d442-431a-bc3c-7f08618ed6ac" />
<img width="500"  alt="Screenshot (17)" src="https://github.com/user-attachments/assets/dd182d29-f085-4081-9e89-244fc5dde74e" />
<img width="500"  alt="Screenshot (16)" src="https://github.com/user-attachments/assets/5f94fecc-7a4b-4d1b-be80-db3d9fc6fc13" />
<img width="500"  alt="Screenshot (15)" src="https://github.com/user-attachments/assets/f9c9f724-1bb2-4642-8eb1-d5780ab6d124" />
<img width="500"  alt="Screenshot (14)" src="https://github.com/user-attachments/assets/590427ee-3989-4ae3-ab4f-f42bea712efa" />
<img width="500"  alt="Screenshot (13)" src="https://github.com/user-attachments/assets/c7772f79-7f3a-49e9-ad3b-68bd7645681f" />
<img width="500"  alt="Screenshot (12)" src="https://github.com/user-attachments/assets/07c567e2-47ca-48d2-9c09-e790b51882db" />
<img width="500"  alt="Screenshot (11)" src="https://github.com/user-attachments/assets/ecd78bde-01de-4966-882c-6005dd418707" />
<img width="500"  alt="Screenshot (10)" src="https://github.com/user-attachments/assets/5682c98c-602e-4626-826f-a041f5479d2e" />
<img width="500"  alt="Screenshot (9)" src="https://github.com/user-attachments/assets/4939177a-5b16-4824-8c3a-df1e02e61620" />
<img width="500"  alt="Screenshot (8)" src="https://github.com/user-attachments/assets/f2ce779d-3cd0-4c8f-96fc-de8f8e35e3cd" />
<img width="500"  alt="Screenshot (121)" src="https://github.com/user-attachments/assets/47bc7756-2fe0-4c07-9ba7-63afa323c12e" />










