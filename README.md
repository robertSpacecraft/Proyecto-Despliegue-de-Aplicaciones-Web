# Proyecto Despliegue de Aplicaciones Web - Roberto

Este proyecto consiste en el despliegue de una aplicación de gestión de películas usando una arquitectura de microservicios con Docker.

## Estructura del Proyecto
- **/frontend**: Servidor Apache con la interfaz web (Puerto 80).
- **/backend**: API en PHP y Apache (Puerto 8080).
- **/db**: Base de datos MySQL.
- **/mantenimiento**: Servicio independiente para estados de parada técnica.

## Cómo ejecutar (Producción)
```bash
docker compose -f backend/docker-compose.prod.yml up -d
docker compose -f frontend/docker-compose.prod.yml up -d
