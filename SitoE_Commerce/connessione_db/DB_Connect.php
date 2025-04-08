<?php
require_once "functions.php";
class DataBase_Connect
{
    private static PDO $db;
    public static function getDB(array $config) : PDO
    {
        if (!isset(self::$db))
        {
            try
            {
                self::$db = new PDO($config['dns'], $config['username'], $config['password'], $config['options']);
            }
            catch (PDOException $ex)
            {
                logError($ex);
            }

        }
        return self::$db;
    }
}