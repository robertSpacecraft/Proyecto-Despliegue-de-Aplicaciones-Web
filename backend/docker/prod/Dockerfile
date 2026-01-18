FROM debian:12-slim

LABEL maintainer="robamolin@alu.edu.gva.es"
ENV DEBIAN_FRONTEND=noninteractive

RUN apt-get update && apt-get upgrade -y \
    && apt-get install -y \
        apache2 \
        php \
        libapache2-mod-php \
        php-mysql \
        php-yaml \
    && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite headers

WORKDIR /var/www/html

# Copio el código del backend (imagen inmutable)
COPY api/ /var/www/html/
COPY utils/ /var/www/utils/
COPY dbconfiguration.yml /var/www/dbconfiguration.yml

# VirtualHost de producción
COPY docker/prod/000-default.conf /etc/apache2/sites-available/000-default.conf
RUN a2ensite 000-default.conf

EXPOSE 80

CMD ["apachectl", "-D", "FOREGROUND"]
