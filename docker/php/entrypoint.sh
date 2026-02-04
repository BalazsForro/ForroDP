#!/usr/bin/env sh
set -e

cd /var/www/html

if [ ! -f vendor/autoload.php ]; then
  echo "[entrypoint] Installing PHP dependencies (composer install)..."
  composer install --no-interaction --prefer-dist
fi

exec php-fpm
