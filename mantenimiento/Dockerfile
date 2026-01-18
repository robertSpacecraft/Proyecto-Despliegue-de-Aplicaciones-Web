FROM php:8.2-apache

# Habilito mod_rewrite por si acaso
RUN a2enmod rewrite
COPY ./public_html /var/www/html
EXPOSE 80
