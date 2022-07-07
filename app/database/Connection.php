<?php
namespace app\database;

use PDO;

class Connection
{
    private static $pdoInstance = null;

    public static function getConnection()
    {
        if (empty(self::$pdoInstance)) {
            self::$pdoInstance = new PDO("mysql:host=localhost;dbname=lumen", "root", "", [
                PDO::ATTR_DEFAULT_FETCH_MODE =>PDO::FETCH_OBJ
            ]);
        }

        return self::$pdoInstance;
    }
}
