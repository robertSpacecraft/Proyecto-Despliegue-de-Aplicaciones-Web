FROM php:8.2-apache

LABEL maintainer="robamolin@alu.edu.gva.es"
RUN a2enmod rewrite headers

WORKDIR /var/www/html

COPY public_html/ /var/www/html/
COPY public_html/config/urls.prod.js /var/www/html/config/urls.js

COPY virtualhosts/prod/000-default.conf /etc/apache2/sites-available/000-default.conf

EXPOSE 80

CMD ["apache2-foreground"]