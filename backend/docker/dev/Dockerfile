FROM debian:12-slim

ENV DEBIAN_FRONTEND=noninteractive

RUN apt-get update && apt-get upgrade -y \
    && apt-get install -y \
        apache2 \
        php \
        libapache2-mod-php \
        php-mysql \
        php-yaml \
        curl \
    && rm -rf /var/lib/apt/lists/*

# Módulos necesarios (rewrite lo usaremos después para htaccess; headers para CORS)
RUN a2enmod rewrite headers

WORKDIR /var/www/html

COPY docker/dev/000-default.conf /etc/apache2/sites-available/000-default.conf
RUN a2ensite 000-default.conf

EXPOSE 80

CMD ["apachectl", "-D", "FOREGROUND"]
