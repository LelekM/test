<?php

namespace App\Services;

use PDO;
use PDOException;

class Db
{
    private static ?PDO $db = null;

    public static function get()
    {
        if (is_null(static::$db)) {
            try {
                static::$db = new PDO("mysql:host=localhost;dbname=lelek", 'baza', '1234');
                static::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }
        }
        return static::$db;
    }
}