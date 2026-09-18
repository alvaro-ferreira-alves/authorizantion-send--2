FROM php:7.4-apache
COPY . /var/www/html

# Corrige os repositórios do Debian Buster (EOL) para usar o archive
RUN sed -i 's/deb.debian.org/archive.debian.org/g; s|security.debian.org|archive.debian.org|g; s|stretch/updates|buster/updates|g' /etc/apt/sources.list \
    && sed -i '/buster-updates/d' /etc/apt/sources.list

RUN apt-get update && apt-get install -y \
    libpq-dev \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo pdo_pgsql

COPY . /var/www/html
WORKDIR /var/www/html
RUN a2enmod rewrite
EXPOSE 80
CMD ["apache2-foreground"]
