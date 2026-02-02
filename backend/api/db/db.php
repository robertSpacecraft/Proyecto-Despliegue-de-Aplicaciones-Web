<?php

function getDBConfig() {
    // Si existen variables de entorno (Render/AWS/Aiven), las priorizo
    if (getenv("DB_HOST")) {
        $cad = sprintf("mysql:dbname=%s;host=%s;port=%s;charset=UTF8", 
            getenv("DB_NAME"), 
            getenv("DB_HOST"), 
            getenv("DB_PORT") ?: "3306"
        );
        return array(
            "cad" => $cad,
            "user" => getenv("DB_USER"),
            "pass" => getenv("DB_PASS")
        );
    }

    // Si no, cargamos el archivo YML local
    $dbFileConfig = dirname(__FILE__) . "/../../dbconfiguration.yml";
    if (file_exists($dbFileConfig)) {
        $configYML = yaml_parse_file($dbFileConfig);
        $cad = sprintf("mysql:dbname=%s;host=%s;charset=UTF8", $configYML["dbname"], $configYML["ip"]);
        return array(
            "cad" => $cad,
            "user" => $configYML["user"],
            "pass" => $configYML["pass"]
        );
    }
    
    return null;
}

function getDBConnection() {
    try {
        $res = getDBConfig();
        if (!$res) return null;

        $options = array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        );

        return new PDO($res["cad"], $res["user"], $res["pass"], $options);
    } catch(PDOException $e) {
        return null;
    }
}

/* ------------ PELÍCULAS  --------------- */

function getFilmsDB() {
    try {
        $bd = getDBConnection();
        if (!$bd) return null;

        $sqlPrepared = $bd->prepare("SELECT id, name, director, classification, img FROM film");
        $sqlPrepared->execute();
        return $sqlPrepared->fetchAll();
    } catch (PDOException $e) {
        return null;
    }
}

function getFilmDB($id) {
    try {
        $bd = getDBConnection();
        if (!$bd) return null;

        $sqlPrepared = $bd->prepare("SELECT * FROM film WHERE id = :id");
        $sqlPrepared->execute([':id' => $id]);
        return $sqlPrepared->fetchAll(); // Mantenemos fetchAll para no romper tu lógica de count() > 0
    } catch (PDOException $e) {
        return null;
    }
}

function addFilmDB($data) {
    try {
        $bd = getDBConnection();
        if (!$bd) return null;

        $sqlPrepared = $bd->prepare("
            INSERT INTO film (name, director, classification, img, plot)
            VALUES (:name, :director, :classification, :img, :plot)
        ");

        $params = array(
            ':name'           => $data["name"],
            ':director'       => $data["director"],
            ':classification' => $data["classification"],
            ':img'            => $data["img"],
            ':plot'           => $data["plot"]
        );

        $sqlPrepared->execute($params);
        return $sqlPrepared->rowCount(); // Ahora sí devuelve el número de filas insertadas
    } catch (PDOException $e) {
        return null;
    }
}

function updateFilmDB($data) {
    try {
        $bd = getDBConnection();
        if (!$bd) return null;

        $sqlPrepared = $bd->prepare("
            UPDATE film
            SET name=:name, director=:director, classification=:classification, img=:img, plot=:plot
            WHERE id=:id
        ");

        $params = array(
            ':name'           => $data["name"],
            ':director'       => $data["director"],
            ':classification' => $data["classification"],
            ':img'            => $data["img"],
            ':plot'           => $data["plot"],
            ':id'             => $data["id"]
        );

        $sqlPrepared->execute($params);
        return $sqlPrepared->rowCount();
    } catch (PDOException $e) {
        return null;
    }
}

function deleteFilmDB($id) {
    try {
        $bd = getDBConnection();
        if (!$bd) return null;

        $sqlPrepared = $bd->prepare("DELETE FROM film WHERE id=:id");
        $sqlPrepared->execute([':id' => $id]);
        return $sqlPrepared->rowCount();
    } catch (PDOException $e) {
        return null;
    }
}