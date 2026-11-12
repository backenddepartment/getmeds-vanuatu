#!/bin/sh
# Prepares the persistent volume on every start (a fresh volume is empty),
# then hands over to the official PHP image's entrypoint.
set -e

STATE=/var/lib/getmeds
mkdir -p "$STATE/uploads" "$STATE/cache"
touch "$STATE/orders.log" "$STATE/enquiries.log"
chown -R www-data:www-data "$STATE"
chmod 700 "$STATE" "$STATE/uploads"
chmod 600 "$STATE/orders.log" "$STATE/enquiries.log"

exec docker-php-entrypoint "$@"
