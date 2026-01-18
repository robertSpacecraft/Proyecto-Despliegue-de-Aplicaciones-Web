FROM php:8.2-apache

# Etiqueta obligatoria del PDF
LABEL maintainer="robamolin@alu.edu.gva.es"

# Habilitamos módulos necesarios
RUN a2enmod rewrite headers

# Permisos correctos para la carpeta de trabajo
WORKDIR /var/www/html

EXPOSE 80

CMD ["apache2-foreground"]
