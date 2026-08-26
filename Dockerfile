FROM php:8.2-apache

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libsqlite3-dev \
    sqlite3 \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql pdo_sqlite \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Copy application files to the web server root
COPY . /var/www/html/

# Create a persistent /data directory for SQLite database (outside web root)
# On Render, mount a Persistent Disk to /data to preserve data across deploys
RUN mkdir -p /data \
    && chown -R www-data:www-data /data \
    && chmod 755 /data

# Set correct permissions for Apache
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod 644 /var/www/html/includes/church.db || true

# Copy the existing DB to /data if it exists (initial seed)
RUN cp /var/www/html/includes/church.db /data/church.db 2>/dev/null || true \
    && chown www-data:www-data /data/church.db 2>/dev/null || true

# Expose port 80
EXPOSE 80
