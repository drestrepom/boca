#!/bin/bash
set -e

# Default environment variable fallbacks
default_db_host="${DB_HOST:-db}"
default_db_port="${DB_PORT:-5432}"
default_db_user="${DB_USER:-bocauser}"
default_db_pass="${DB_PASSWORD:-boca}"
default_db_name="${DB_NAME:-bocadb}"

# Wait for PostgreSQL to become available
echo "Waiting for PostgreSQL at $default_db_host:$default_db_port..."
until PGPASSWORD="$default_db_pass" psql -h "$default_db_host" -U "$default_db_user" -d "$default_db_name" -c '\q' 2>/dev/null; do
  sleep 1
done
echo "PostgreSQL is up."

# Check if BOCA tables have been created
echo "Checking BOCA schema..."
if ! PGPASSWORD="$default_db_pass" psql -h "$default_db_host" -U "$default_db_user" -d "$default_db_name" -tAc "SELECT 1 FROM pg_tables WHERE schemaname='public' AND tablename='contesttable'" | grep -q 1; then
  echo "Initializing BOCA database schema..."
  echo YES | php /var/www/html/private/createdb.php
else
  echo "BOCA schema already initialized."
fi

# Launch Apache in foreground
exec apache2-foreground
