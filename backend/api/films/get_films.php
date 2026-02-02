<?php
require_once __DIR__ . "/../../utils/utils.php";
require_once __DIR__ . "/../db/db.php";
header('Content-Type: application/json');

try {
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        $resp = getFilmsDB(); // Llama a la función del listado (plural)
        if (is_null($resp)) {
            echo getResponse(500, "KO", "Error de conexión BD");
        } else {
            echo getResponse(200, "OK", "Lista cargada", $resp);
        }
    }
} catch (Exception $e) {
    echo getResponse(500, "KO", $e->getMessage());
}