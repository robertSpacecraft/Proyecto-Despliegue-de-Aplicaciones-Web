FROM debian:12-slim

LABEL maintainer="robamolin@alu.edu.gva.es"
ENV DEBIAN_FRONTEND=noninteractive

# Instalación de dependencias (PHP, Apache y extensiones)
RUN apt-get update && apt-get upgrade -y \
    && apt-get install -y \
        apache2 \
        php \
        libapache2-mod-php \
        php-mysql \
        php-yaml \
    && rm -rf /var/lib/apt/lists/*

# Activación de módulos de Apache
RUN a2enmod rewrite headers

# Directorio de trabajo
WORKDIR /var/www/html

# Limpieza preventiva de la raíz de Apache
RUN rm -rf /var/www/html/*

# 1. Copiamos la lógica del Backend (API y Utils)
# Los archivos de backend/api/ se vuelcan en la raíz para que las rutas coincidan
COPY backend/api/ /var/www/html/
COPY backend/utils/ /var/www/utils/

# 2. Copiamos el Frontend
# El index.html quedará en /var/www/html/index.html
COPY frontend/public_html/ /var/www/html/

# 3. Copiamos archivos de configuración
COPY backend/dbconfiguration.prod.yml /var/www/dbconfiguration.yml
COPY backend/docker/prod/000-default.conf /etc/apache2/sites-available/000-default.conf

# 4. AJUSTE DE PERMISOS (Vital para corregir el error 403)
# Asegura que Apache (www-data) sea el dueño de los archivos
RUN chown -R www-data:www-data /var/www/html /var/www/utils

# Activación del VirtualHost configurado
RUN a2ensite 000-default.conf

EXPOSE 80

# Ejecución de Apache en primer plano
CMD ["apachectl", "-D", "FOREGROUND"]