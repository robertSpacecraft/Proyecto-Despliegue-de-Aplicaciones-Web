<?php
require_once __DIR__ . "/../../utils/utils.php";
require_once __DIR__ . "/../db/db.php";

// Cabecera para asegurar que la respuesta sea JSON
header('Content-Type: application/json');

try {
    if ($_SERVER["REQUEST_METHOD"] == "GET") {

        // Llamamos a la función que trae TODAS las películas (en plural)
        $resp = getFilmsDB();

        if (is_null($resp)) {
            // Si la conexión a la DB falla, getFilmsDB devuelve null
            echo getResponse(500, "KO", "Error interno de base de datos - No se pudo conectar");
        } else {
            // Aunque la lista esté vacía (count == 0), es una respuesta válida (200 OK)
            echo getResponse(200, "OK", "Películas obtenidas correctamente", $resp);
        }

    } else {
        echo getResponse(405, "KO_REQUEST", "Tipo de petición incorrecta. Se esperaba GET.");
    }

} catch (Exception $e) {
    // Logueamos el error real en Render para depurar
    error_log("Error en get_films.php: " . $e->getMessage());
    echo getResponse(500, "KO", "Error interno del servidor");
}