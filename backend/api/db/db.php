<?php

function getDBConfig() {
    // 1. Prioridad: Variables de entorno (Producción en Render/Aiven)
    if (getenv("DB_HOST")) {
        return array(
            "cad" => sprintf("mysql:dbname=%s;host=%s;port=%s;charset=UTF8", 
                getenv("DB_NAME"), 
                getenv("DB_HOST"), 
                getenv("DB_PORT") ?: "3306"
            ),
            "user" => getenv("DB_USER"),
            "pass" => getenv("DB_PASS"),
            "ssl"  => true
        );
    }

    // 2. Fallback: Configuración local (YAML)
    // Usamos una ruta absoluta común en contenedores o relativa al archivo
    $dbFileConfig = dirname(__FILE__) . "/../../dbconfiguration.yml";
    
    if (file_exists($dbFileConfig)) {
        // Verificamos que la función exista para evitar Error 500 si no está instalada la extensión
        if (function_exists('yaml_parse_file')) {
            $configYML = yaml_parse_file($dbFileConfig);
            return array(
                "cad" => sprintf("mysql:dbname=%s;host=%s;charset=UTF8", $configYML["dbname"], $configYML["ip"]),
                "user" => $configYML["user"],
                "pass" => $configYML["pass"],
                "ssl"  => false
            );
        }
    }
    
    return null;
}

function getDBConnection() {
    try {
        $res = getDBConfig();
        if (!$res) return null;

        $options = array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            // Necesario para Aiven: no verificar certificado CA si no tenemos el archivo .pem
            PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
        );

        $connString = $res["cad"];
        
        // Si la configuración indica SSL (Aiven), añadimos el flag a la cadena
        if ($res["ssl"]) {
            $connString .= ";sslmode=verify-ca";
        }

        return new PDO($connString, $res["user"], $res["pass"], $options);
    } catch(PDOException $e) {
        // En producción, es mejor no mostrar el error real por seguridad, 
        // pero puedes loguearlo aquí si fuera necesario: error_log($e->getMessage());
        return null;
    }
}

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