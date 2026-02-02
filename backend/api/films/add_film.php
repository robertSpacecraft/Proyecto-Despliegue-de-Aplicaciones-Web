<?php
// Rutas absolutas para evitar fallos en entornos Docker/Render
require_once __DIR__ . "/../../utils/utils.php";
require_once __DIR__ . "/../db/db.php";

try {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // Lectura del cuerpo de la petición (JSON)
        $jsonBody = json_decode(file_get_contents('php://input'), true);

        // Validación de datos requeridos
        if (empty($jsonBody["name"]) || empty($jsonBody["director"]) || 
            empty($jsonBody["classification"]) || empty($jsonBody["img"]) || 
            empty($jsonBody["plot"])) {
            
            echo getResponse(400, "KO_MISSING", "Falta algún atributo obligatorio");
            exit;
        }

        // Preparo el array para la DB
        $data = array(
            "name"           => $jsonBody["name"],
            "director"       => $jsonBody["director"],
            "classification" => $jsonBody["classification"],
            "img"            => $jsonBody["img"],
            "plot"           => $jsonBody["plot"],
        );

        $resp = addFilmDB($data);

        if (is_null($resp)) {
            echo getResponse(500, "KO", "Error interno de base de datos");
        } else {
            if ($resp > 0) {
                echo getResponse(201, "OK", "Película añadida correctamente!");
            } else {
                echo getResponse(500, "KO_ADD", "No se pudo insertar la película");
            }
        }

    } else {
        // Si no es POST, respondemos con error de método
        echo getResponse(405, "KO_REQUEST", "Tipo de petición incorrecta. Se esperaba POST.");
    }

} catch (Exception $e) {
    echo getResponse(500, "KO", "Error interno: " . $e->getMessage());
}