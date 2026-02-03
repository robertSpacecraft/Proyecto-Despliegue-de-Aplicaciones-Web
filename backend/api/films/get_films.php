<?php
// Rutas validadas según la estructura: /var/www/html/films/get_films.php
require_once __DIR__ . "/../../utils/utils.php"; 
require_once __DIR__ . "/../db/db.php";

header('Content-Type: application/json');

try {
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        $resp = getFilmsDB(); 

        if (is_null($resp)) {
            // Esto se dispara si getDBConnection() devuelve null
            echo getResponse(500, "KO", "Error de conexión BD - Revisa logs de Render");
        } else {
            // Éxito: incluso si el array está vacío, es un 200 OK
            echo getResponse(200, "OK", "Lista cargada", $resp);
        }
    } else {
        // Si alguien intenta un POST o DELETE, devolvemos un 405 en lugar de nada
        echo getResponse(405, "KO", "Método no permitido. Usa GET.");
    }
} catch (Throwable $e) { 
    // Usamos Throwable para capturar tanto Errores de PHP como Excepciones
    error_log("Fallo en get_films.php: " . $e->getMessage());
    echo getResponse(500, "KO", "Error crítico: " . $e->getMessage());
}