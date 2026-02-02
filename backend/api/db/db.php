<?php

function getDBConfig() {
    if (getenv("DB_HOST")) {
        return [
            "cad" => sprintf("mysql:dbname=%s;host=%s;port=%s;charset=UTF8", 
                getenv("DB_NAME"), getenv("DB_HOST"), getenv("DB_PORT") ?: "3306"),
            "user" => getenv("DB_USER"),
            "pass" => getenv("DB_PASS"),
            "ssl"  => true
        ];
    }
    return null;
}

function getDBConnection() {
    try {
        $res = getDBConfig();
        if (!$res) return null;

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            // Aiven requiere SSL. Esta opción permite conectar aunque no validemos el certificado local
            PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
        ];

        $connString = $res["cad"];
        if ($res["ssl"]) {
            // Forzamos el modo SSL en la cadena de conexión
            $connString .= ";sslmode=required";
        }

        return new PDO($connString, $res["user"], $res["pass"], $options);
    } catch(PDOException $e) {
        // ESTO ES CLAVE: Escribe el error real en los Logs de Render
        error_log("FALLO DE CONEXIÓN DB: " . $e->getMessage());
        return null;
    }
}

// ... resto de funciones (getFilmsDB, etc.)

/* ------------ PELÍCULAS --------------- */

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