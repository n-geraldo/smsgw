# AAA SMS Gateway Manager (Linux Runbook)

Laravel-based admin app to read expiration dates from a DMA Softlab FreeRADIUS Manager database (read-only) and send SMS reminders via a Dinstar SMS Gateway HTTP API. Includes template management, schedules, dashboard, and SMS monitoring.

## Requirements (Linux)

- PHP 8.3+ (project currently on Laravel 12)
- Composer
- MySQL/MariaDB for the app database
- DMA Softlab FreeRADIUS Manager database access (read-only user)
- A running Dinstar SMS Gateway HTTP API

Optional:
- Node.js + npm (only needed if you choose to build assets; UI uses Bootstrap CDN)

## 1) Clone and install

```bash
git clone <repo-url> smsgateway
cd smsgateway
composer install
```

## 2) Configure environment

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` (examples):

```
APP_NAME="AAA SMS Gateway Manager"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=smsgateway
DB_USERNAME=root
DB_PASSWORD=

DMA_DB_HOST=127.0.0.1
DMA_DB_PORT=3306
DMA_DB_DATABASE=dma
DMA_DB_USERNAME=dma_readonly
DMA_DB_PASSWORD=

QUEUE_CONNECTION=database

ADMIN_NAME=Admin
ADMIN_EMAIL=admin@example.com
ADMIN_PASSWORD=change-me

SMS_COMPANY_NAME="AAA"
SMS_RATE_LIMIT_PER_MINUTE=60
```

## 3) Prepare database

Create the app database in MySQL/MariaDB:

```bash
mysql -u root -p -e "CREATE DATABASE smsgateway CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

Run migrations and seed default data:

```bash
php artisan migrate --seed
```

This seeds:
- Default admin user (from `.env`)
- Default templates (7/3/1 days)
- Default schedules

## 4) Set permissions (Linux)

```bash
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

If you run PHP as a different user, replace `www-data` accordingly.

## 5) Run the app (development)

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

Open: `http://127.0.0.1:8000`

Login with the admin credentials from `.env`.

## 6) Run the queue worker

```bash
php artisan queue:work --tries=3 --sleep=2
```

Leave this running in a separate terminal or supervise it with systemd/supervisord in production.

## 7) Enable scheduler (cron)

Edit crontab:

```bash
crontab -e
```

Add:

```bash
* * * * * cd /path/to/smsgateway && php artisan schedule:run >> /dev/null 2>&1
```

This triggers the hourly `sms:scan-expirations` command via the scheduler.

## 8) Configure DMA Mapping in the UI

In the admin panel:
- DMA Config shows the `.env` connection (read-only)
- DMA Mapping lets you set:
  - Table name
  - Username column
  - Phone column
  - Expiration date column
  - Optional status column and active values

The app validates table/column names and uses a read-only connection. Use a DMA DB user with SELECT-only permissions.

## 9) Configure SMS Gateway

In **Gateways**, set:
- Base URL (e.g., `http://gateway-host:port`)
- Endpoint path (e.g., `/api/send`)
- Auth type (`none`, `basic`, `bearer`)
- Request type (`json` or `query`)
- Sender ID

Payload mapping:
- `to` -> destination number
- `message` -> SMS body
- `senderId` -> sender ID

If your Dinstar API differs, adjust `app/Services/DinstarGatewayClient.php`.

## 10) Test run

From CLI:

```bash
php artisan sms:scan-expirations
```

From UI:
- Use **Test SMS** page to send a test message
- Check **SMS Monitor** for logs

## Production notes

- Use a dedicated read-only DMA DB user.
- Run queue workers under a process manager (systemd/supervisord).
- Set `APP_ENV=production`, `APP_DEBUG=false`.
- Consider HTTPS termination via Nginx/Apache.
