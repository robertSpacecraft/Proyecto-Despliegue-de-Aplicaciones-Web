FROM debian:12-slim

ENV DEBIAN_FRONTEND=noninteractive

RUN apt-get update && apt-get upgrade -y \
    && apt-get install -y apache2 php libapache2-mod-php php-mysql php-yaml \
    && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite headers

WORKDIR /var/www/html

# BORRADO TOTAL de la carpeta para que no quede nada de Debian/Apache
RUN rm -rf /var/www/html/*

# 1. Copiamos el FRONTEND primero
COPY frontend/public_html/ /var/www/html/

# 2. Copiamos la API (esto creará la carpeta /var/www/html/films/)
COPY backend/api/ /var/www/html/

# 3. Copiamos UTILS (fuera de la carpeta pública por seguridad)
RUN mkdir -p /var/www/utils
COPY backend/utils/ /var/www/utils/

# 4. Copiamos Configuración
COPY backend/docker/prod/000-default.conf /etc/apache2/sites-available/000-default.conf

# 5. PERMISOS TOTALES (Solución definitiva al 403)
RUN chown -R www-data:www-data /var/www/html /var/www/utils
RUN chmod -R 755 /var/www/html

RUN a2ensite 000-default.conf

EXPOSE 80
CMD ["apachectl", "-D", "FOREGROUND"]