FROM php:7.4-apache

# Corrige os repositórios do Debian Buster (EOL) para usar o archive
RUN sed -i -e 's/deb.debian.org/archive.debian.org/g' \
           -e 's|security.debian.org|archive.debian.org/debian-security|g' \
           -e '/buster-updates/d' \
           /etc/apt/sources.list

RUN apt-get clean \
    && apt-get update -o Acquire::Check-Valid-Until=false \
    && apt-get install -y --no-install-recommends libpq-dev \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo pdo_pgsql

COPY . /var/www/html
WORKDIR /var/www/html
RUN a2enmod rewrite
EXPOSE 80
CMD ["apache2-foreground"]
