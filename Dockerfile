FROM php:7.4-apache

# Aponta os repositórios para o archive (Bullseye já saiu do ciclo normal de updates)
# e REMOVE a linha de security, que não existe nesse formato no archive
RUN sed -i -e 's/deb.debian.org/archive.debian.org/g' \
           -e '/security/d' \
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
