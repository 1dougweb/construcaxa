#!/bin/bash
set -e

# Create SQLite database if using SQLite
if [ "${DB_CONNECTION:-sqlite}" = "sqlite" ]; then
    mkdir -p /var/www/database
    if [ ! -f /var/www/database/database.sqlite ]; then
        touch /var/www/database/database.sqlite
        chown -R www:www /var/www/database
        chmod 664 /var/www/database/database.sqlite
    fi
fi

# Start supervisor
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf

