<?php
// backend/api/setup.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . "/db/db.php";

echo "<h2>Martillo de Thor: Constructor de Tablas</h2>";

$db = getDBConnection();

if (!$db) {
    die("❌ No se pudo conectar a la base de datos. Revisa las variables en Render.");
}

$sql = "CREATE TABLE IF NOT EXISTS film (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    director VARCHAR(255),
    classification VARCHAR(50),
    img VARCHAR(555),
    plot TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

try {
    $db->exec($sql);
    echo "✅ Tabla 'film' creada o ya existía.<br>";
    
    // Insertamos una película de prueba solo si la tabla está vacía
    $check = $db->query("SELECT COUNT(*) FROM film")->fetchColumn();
    if ($check == 0) {
        $db->exec("INSERT INTO film (name, director, classification, img, plot) 
                   VALUES ('Origen', 'Christopher Nolan', '7+', 'https://m.media-amazon.com/images/I/912AErFSBHL._AC_SL1500_.jpg', 'Un ladrón que roba secretos a través de los sueños.')");
        echo "✅ Película de prueba insertada.";
    }
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage();
}