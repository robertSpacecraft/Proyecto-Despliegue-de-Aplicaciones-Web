<?php
require_once __DIR__ . "/../../utils/utils.php";
require_once __DIR__ . "/../db/db.php";

try {
    if ($_SERVER["REQUEST_METHOD"] == "PUT") {

        // Recibir y decodificar el JSON
        $jsonBody = json_decode(file_get_contents('php://input'), true);

        // Validación de todos los campos necesarios, incluyendo el ID
        if (empty($jsonBody["id"]) || empty($jsonBody["name"]) || 
            empty($jsonBody["director"]) || empty($jsonBody["classification"]) || 
            empty($jsonBody["img"]) || empty($jsonBody["plot"])) {
            
            echo getResponse(400, "KO_MISSING", "Falta algún atributo obligatorio para la actualización");
            exit;
        }

        $data = array(
            "id"             => $jsonBody["id"],
            "name"           => $jsonBody["name"],
            "director"       => $jsonBody["director"],
            "classification" => $jsonBody["classification"],
            "img"            => $jsonBody["img"],
            "plot"           => $jsonBody["plot"]
        );

        $resp = updateFilmDB($data);

        if (is_null($resp)) {
            echo getResponse(500, "KO", "Error interno de base de datos");
        } else {
            echo getResponse(200, "OK", "Proceso de actualización completado");
        }

    } else {
        echo getResponse(405, "KO_REQUEST", "Tipo de petición incorrecta. Se esperaba PUT.");
    }

} catch (Exception $e) {
    echo getResponse(500, "KO", "Error interno del servidor");
}