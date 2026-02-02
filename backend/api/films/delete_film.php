<?php
require_once __DIR__ . "/../../utils/utils.php";
require_once __DIR__ . "/../db/db.php";

try {
    if ($_SERVER["REQUEST_METHOD"] == "DELETE") {

        // Decodificamos el cuerpo JSON de la petición
        $jsonBody = json_decode(file_get_contents('php://input'), true);

        // Validación del ID
        if (empty($jsonBody["id"])) {
            echo getResponse(400, "KO_MISSING", "Falta el atributo id para realizar la eliminación");
            exit;
        }

        $resp = deleteFilmDB($jsonBody["id"]);

        if (is_null($resp)) {
            echo getResponse(500, "KO", "Error interno de base de datos");
        } else {
            if ($resp > 0) {
                echo getResponse(200, "OK", "Película eliminada correctamente!");
            } else {
                echo getResponse(404, "KO_NOT_FOUND", "No se encontró ninguna película con ese ID para eliminar");
            }
        }

    } else {
        echo getResponse(405, "KO_REQUEST", "Tipo de petición incorrecta. Se esperaba DELETE.");
    }

} catch (Exception $e) {
    echo getResponse(500, "KO", "Error interno del servidor");
}