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

Update database config in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=laravel
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

### 6. Run migrations
```bash
docker compose exec app php artisan migrate
```

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
