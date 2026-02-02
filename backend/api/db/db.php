<?php

/**
 * Obtiene la configuración de la base de datos desde variables de entorno.
 */
function getDBConfig() {
    if (getenv("DB_HOST")) {
        return [
            "host" => getenv("DB_HOST"),
            "name" => getenv("DB_NAME"),
            "user" => getenv("DB_USER"),
            "pass" => getenv("DB_PASS"),
            "port" => getenv("DB_PORT") ?: "3306"
        ];
    }
    return null;
}

/**
 * Crea una conexión PDO configurada para entornos con SSL (Aiven).
 */
function getDBConnection() {
    try {
        $config = getDBConfig();
        if (!$config) return null;

        $dsn = sprintf("mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4", 
            $config['host'], $config['port'], $config['name']);

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            // Clave para Aiven: permite SSL sin validar el certificado CA localmente
            PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
        ];

        return new PDO($dsn, $config['user'], $config['pass'], $options);
    } catch (PDOException $e) {
        // Registra el error en los logs de Render para depuración
        error_log("ERROR DB CONNECTION: " . $e->getMessage());
        return null;
    }
}

/* ------------ FUNCIONES DE PELÍCULAS --------------- */

/**
 * Obtiene el listado completo de películas para la tabla principal.
 */
function getFilmsDB() {
    try {
        $db = getDBConnection();
        if (!$db) return null;
        return $db->query("SELECT id, name, director, classification, img FROM film")->fetchAll();
    } catch (PDOException $e) {
        error_log("ERROR getFilmsDB: " . $e->getMessage());
        return null;
    }
}

/**
 * Obtiene el detalle de una película específica por su ID.
 */
function getFilmDB($id) {
    try {
        $db = getDBConnection();
        if (!$db) return null;
        $stmt = $db->prepare("SELECT * FROM film WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetchAll(); // Se devuelve fetchAll para mantener compatibilidad con el resto del código
    } catch (PDOException $e) {
        error_log("ERROR getFilmDB: " . $e->getMessage());
        return null;
    }
}

/**
 * Inserta una nueva película en la base de datos.
 */
function addFilmDB($data) {
    try {
        $db = getDBConnection();
        if (!$db) return null;
        $stmt = $db->prepare("INSERT INTO film (name, director, classification, img, plot) 
                            VALUES (:name, :director, :classification, :img, :plot)");
        $stmt->execute([
            'name'           => $data["name"], 
            'director'       => $data["director"],
            'classification' => $data["classification"], 
            'img'            => $data["img"], 
            'plot'           => $data["plot"]
        ]);
        return $stmt->rowCount();
    } catch (PDOException $e) {
        error_log("ERROR addFilmDB: " . $e->getMessage());
        return null;
    }
}

/**
 * Actualiza los datos de una película existente.
 */
function updateFilmDB($data) {
    try {
        $db = getDBConnection();
        if (!$db) return null;
        $stmt = $db->prepare("UPDATE film SET name=:name, director=:director, 
                            classification=:classification, img=:img, plot=:plot WHERE id=:id");
        $stmt->execute([
            'name'           => $data["name"], 
            'director'       => $data["director"],
            'classification' => $data["classification"], 
            'img'            => $data["img"],
            'plot'           => $data["plot"], 
            'id'             => $data["id"]
        ]);
        return $stmt->rowCount();
    } catch (PDOException $e) {
        error_log("ERROR updateFilmDB: " . $e->getMessage());
        return null;
    }
}

/**
 * Elimina una película de la base de datos.
 */
function deleteFilmDB($id) {
    try {
        $db = getDBConnection();
        if (!$db) return null;
        $stmt = $db->prepare("DELETE FROM film WHERE id=:id");
        $stmt->execute(['id' => $id]);
        return $stmt->rowCount();
    } catch (PDOException $e) {
        error_log("ERROR deleteFilmDB: " . $e->getMessage());
        return null;
    }
}