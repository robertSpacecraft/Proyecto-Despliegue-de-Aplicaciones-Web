<?php

/**
 * Genera una respuesta JSON estandarizada.
 * Se usa con 'return' cuando quiero que el flujo continúe o se controle externamente.
 */
function getResponse($code = 200, $status = "", $message = "", $data = "") {
    header("Content-Type: application/json; charset=utf-8");
    http_response_code($code);

    $response = array(
        "status"  => $status,
        "message" => $message,
        "data"    => $data
    );

    return json_encode($response, JSON_UNESCAPED_UNICODE);
}

/**
 * Envía la respuesta JSON y finaliza la ejecución.
 * Útil para errores rápidos o salidas directas (evita el error de función no encontrada).
 */
function cabecera($status, $message, $data, $code = 200) {
    echo getResponse($code, $status, $message, $data);
    exit; // Detiene la ejecución para evitar que se envíe contenido extra
}