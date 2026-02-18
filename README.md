# Laravel 12 – Docker Development Environment

Basic Dockerized development setup for a Laravel 12 project using PHP 8.3, MySQL, and Nginx.

---

## Requirements

- Docker Desktop (Docker Compose v2)
- Git

Verify Docker installation:

```bash
docker --version
docker compose version
```

---

## Services

| Service | Description | Port |
|------|-----------|------|
| app | PHP 8.3 FPM + Composer | — |
| web | Nginx | http://localhost:8080 |
| db | MySQL 8 | 3307 → 3306 |

---

## Setup

### 1. Clone the repository
```bash
git clone <your-repo-url>
cd <project-folder>
```

### 2. Create environment file
```bash
cp .env.example .env
```

Update `.env` with the following values:

**Database:**
```env
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=laravel
```

**App name:**
```env
APP_NAME=PulseIO
```

**Local admin auto-login** *(used by the seeder to create the default admin account)*:
```env
ADMIN_LOCAL_LOGIN_EMAIL=admin@localhost.com
ADMIN_LOCAL_LOGIN_PASSWORD=admin123admin
```

**Device API** *(endpoint and token used by the simulated device data command)*:
```env
DEVICE_ENDPOINT=http://host.docker.internal:8080/api/set/data
DEVICE_TOKEN=nemtudom
```

---

## Start Development Environment

### 3. Build and start containers
```bash
docker compose up -d --build
```

### 4. Install dependencies
```bash
docker compose exec app composer install
```

### 5. Generate app key
```bash
docker compose exec app php artisan key:generate
```

### 6. Run migrations & seed
```bash
docker compose exec app php artisan migrate --seed
```

This creates the admin user defined by `ADMIN_LOCAL_LOGIN_EMAIL` / `ADMIN_LOCAL_LOGIN_PASSWORD`.

---

## Access

- Application: http://localhost:8080
- MySQL:
    - Host: 127.0.0.1
    - Port: 3307
    - User: laravel
    - Password: laravel
    - Database: laravel

---

## Simulating Device Data

The `simulate:device-data` command sends fake sensor readings to the local API, so you can test the dashboard and charts without real hardware.

It POSTs two random values to the endpoint defined in `DEVICE_ENDPOINT` using the bearer token from `DEVICE_TOKEN`:

| Sensor key | Range |
|------------|-------|
| `test_sensor` | 0 – 100 |
| `test_sensor_2` | 0 – 10 |

**Run once:**
```bash
docker compose exec app php artisan simulate:device-data
```

**Run continuously on a schedule** (every minute via Laravel scheduler):
```bash
docker compose exec app php artisan schedule:work
```

> Make sure `DEVICE_ENDPOINT` and `DEVICE_TOKEN` are set in `.env` before running.

---

## Useful Commands

Stop containers:
```bash
docker compose down
```

Stop containers and remove volumes (⚠ deletes DB data):
```bash
docker compose down -v
```

Run artisan commands:
```bash
docker compose exec app php artisan <command>
```

---
