<?php
require_once __DIR__ . "/../../utils/utils.php";
require_once __DIR__ . "/../db/db.php";

header('Content-Type: application/json');

try {
    if ($_SERVER["REQUEST_METHOD"] == "GET") {

        // 1. Validamos que el ID venga en la URL (?id=X)
        if (empty($_GET["id"])) {
            echo getResponse(400, "KO_MISSING", "Falta el atributo id");
            exit;
        }

        // 2. Llamada a la función que busca una sola película
        $resp = getFilmDB($_GET["id"]);

        if (is_null($resp)) {
            echo getResponse(500, "KO", "Error interno de base de datos");
        } else {
            // getFilmDB devuelve un fetchAll, por lo que miramos si hay al menos un resultado
            if (count($resp) > 0) {
                // Devolvemos solo el primer objeto del array [0]
                echo getResponse(200, "OK", "Película obtenida correctamente", $resp[0]);
            } else {
                echo getResponse(404, "KO_NOT_FOUND", "Película no encontrada");
            }
        }

    } else {
        echo getResponse(405, "KO_REQUEST", "Tipo de petición incorrecta. Se esperaba GET.");
    }

} catch (Exception $e) {
    error_log("Error en get_film.php: " . $e->getMessage());
    echo getResponse(500, "KO", "Error interno del servidor");
}