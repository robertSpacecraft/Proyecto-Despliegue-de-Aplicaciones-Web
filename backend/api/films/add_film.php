<?php
require_once __DIR__ . "/../../utils/utils.php";
require_once __DIR__ . "/../db/db.php";

header('Content-Type: application/json');

try {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Leemos el JSON enviado por el frontend
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        if (!$data) {
            echo getResponse(400, "KO_BAD_DATA", "Datos inválidos o vacíos");
            exit;
        }

        // Llamada a la función de inserción en db.php
        $result = addFilmDB($data);

        if ($result) {
            echo getResponse(201, "OK", "Película añadida con éxito");
        } else {
            echo getResponse(500, "KO", "No se pudo insertar la película");
        }
    } else {
        echo getResponse(405, "KO_METHOD", "Método no permitido");
    }
} catch (Exception $e) {
    error_log("Error en add_film.php: " . $e->getMessage());
    echo getResponse(500, "KO", "Error interno al procesar la solicitud");
}