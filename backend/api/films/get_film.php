<?php
require_once __DIR__ . "/../../utils/utils.php";
require_once __DIR__ . "/../db/db.php";

try {
    if ($_SERVER["REQUEST_METHOD"] == "GET") {

        // Validación del parámetro ID
        if (empty($_GET["id"])) {
            echo getResponse(400, "KO_MISSING", "Falta el atributo id");
            exit;
        }

        // Llamada a la base de datos (función en singular)
        $resp = getFilmDB($_GET["id"]);

        if (is_null($resp)) {
            echo getResponse(500, "KO", "Error interno de base de datos");
        } else {
            if (count($resp) > 0) {
                echo getResponse(200, "OK", "Película obtenida correctamente", $resp[0]);
            } else {
                echo getResponse(404, "KO_NOT_FOUND", "Película no encontrada");
            }
        }

    } else {
        echo getResponse(405, "KO_REQUEST", "Tipo de petición incorrecta. Se esperaba GET.");
    }

} catch (Exception $e) {
    echo getResponse(500, "KO", "Error interno del servidor");
}