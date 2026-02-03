<?php
require_once __DIR__ . "/../../utils/utils.php";
require_once __DIR__ . "/../db/db.php";

header('Content-Type: application/json');

try {
    // Aceptamos GET para borrar por ID rápidamente desde el frontend
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        
        if (empty($_GET["id"])) {
            echo getResponse(400, "KO_MISSING", "Falta el ID de la película");
            exit;
        }

        $id = $_GET["id"];
        $result = deleteFilmDB($id);

        if ($result > 0) {
            echo getResponse(200, "OK", "Película eliminada correctamente");
        } else {
            echo getResponse(404, "KO_NOT_FOUND", "No se encontró la película o ya fue eliminada");
        }

    } else {
        echo getResponse(405, "KO_METHOD", "Método no permitido");
    }
} catch (Exception $e) {
    error_log("Error en delete_film.php: " . $e->getMessage());
    echo getResponse(500, "KO", "Error interno al intentar eliminar");
}