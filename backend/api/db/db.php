<?php
function getDBConfig() {
    if (getenv("DB_HOST")) {
        return [
            "cad" => sprintf("mysql:dbname=%s;host=%s;port=%s;charset=UTF8", 
                getenv("DB_NAME"), getenv("DB_HOST"), getenv("DB_PORT")),
            "user" => getenv("DB_USER"),
            "pass" => getenv("DB_PASS")
        ];
    }
    return null;
}

function getDBConnection() {
    try {
        $res = getDBConfig();
        if (!$res) return null;

        // Intentamos conexión estándar; Aiven suele aceptar SSL implícito 
        // si el cliente lo soporta sin configurar flags adicionales.
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
        ];

        return new PDO($res["cad"], $res["user"], $res["pass"], $options);
    } catch(PDOException $e) {
        error_log("FALLO CRÍTICO DB: " . $e->getMessage());
        return null;
    }
}

/* ------------ FUNCIONES DB --------------- */

function getFilmsDB() {
    try {
        $bd = getDBConnection();
        if (!$bd) return null;
        $sqlPrepared = $bd->prepare("SELECT id, name, director, classification, img FROM film");
        $sqlPrepared->execute();
        return $sqlPrepared->fetchAll();
    } catch (PDOException $e) { return null; }
}

function getFilmDB($id) {
    try {
        $bd = getDBConnection();
        if (!$bd) return null;
        $sqlPrepared = $bd->prepare("SELECT * FROM film WHERE id = :id");
        $sqlPrepared->execute([':id' => $id]);
        return $sqlPrepared->fetchAll();
    } catch (PDOException $e) { return null; }
}

function addFilmDB($data) {
    try {
        $bd = getDBConnection();
        if (!$bd) return null;
        $sqlPrepared = $bd->prepare("INSERT INTO film (name, director, classification, img, plot) VALUES (:name, :director, :classification, :img, :plot)");
        $params = array(
            ':name' => $data["name"], 
            ':director' => $data["director"],
            ':classification' => $data["classification"], 
            ':img' => $data["img"], 
            ':plot' => $data["plot"]
        );
        $sqlPrepared->execute($params);
        return $sqlPrepared->rowCount();
    } catch (PDOException $e) { return null; }
}

function updateFilmDB($data) {
    try {
        $bd = getDBConnection();
        if (!$bd) return null;
        $sqlPrepared = $bd->prepare("UPDATE film SET name=:name, director=:director, classification=:classification, img=:img, plot=:plot WHERE id=:id");
        $params = array(
            ':name' => $data["name"], 
            ':director' => $data["director"],
            ':classification' => $data["classification"], 
            ':img' => $data["img"],
            ':plot' => $data["plot"], 
            ':id' => $data["id"]
        );
        $sqlPrepared->execute($params);
        return $sqlPrepared->rowCount();
    } catch (PDOException $e) { return null; }
}

function deleteFilmDB($id) {
    try {
        $bd = getDBConnection();
        if (!$bd) return null;
        $sqlPrepared = $bd->prepare("DELETE FROM film WHERE id=:id");
        $sqlPrepared->execute([':id' => $id]);
        return $sqlPrepared->rowCount();
    } catch (PDOException $e) { return null; }
}