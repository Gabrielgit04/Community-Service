<?php

// $db_name = 'servicio.db';

define('DB_NAME', 'servicio.db');


function conexionDB()
{

        try {
                $db = new PDO("sqlite:" . __DIR__ . "/" . DB_NAME);
                // Set error mode to exceptions
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);



                return $db;
        } catch (PDOException $e) {
                echo "Error de conexion: " . $e->getMessage();
                exit();
        }
}
