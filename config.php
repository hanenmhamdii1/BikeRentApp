<?php
class Database {
    private static $instance = null;

    public static function connect() {
        if (self::$instance === null) {
            self::$instance = new PDO(
                "mysql:host=localhost;dbname=bike_rental_app;charset=utf8",
                "root",
                "",
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        }
        return self::$instance;
    }
}
