<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . "/db/db.php";

echo "Intentando conectar a: " . getenv("DB_HOST") . "<br>";

$conn = getDBConnection();

if ($conn) {
    echo "✅ CONEXIÓN EXITOSA";
    $query = $conn->query("SELECT 1");
    if ($query) echo "<br>✅ CONSULTA DE PRUEBA OK";
} else {
    echo "❌ FALLO TOTAL. Revisa los logs de Render para ver el mensaje de error de PDO.";
}