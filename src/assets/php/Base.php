<?php
namespace src\assets\php;

use mysqli;
use \src\assets\php\config\params;

class Base
{
    /**
     * @return mysqli|void
     */
    public static function connect()
    {
        $config = PARAMS;

        $connection = new mysqli(
            $config['servername'],
            $config['username'],
            $config['password'],
            $config['dbname']
        );

        if ($connection->connect_error)
            die("Connection failed:" . $connection->connect_error);

        return $connection;
    }

    /**
     * @param $result
     * @return array|false|mixed
     */
    public static function returnQueryResults($result)
    {
        if($result->num_rows == 1) {
            return $result->fetch_assoc();
        }else if ($result->num_rows > 1) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            return $rows;
        } else {
            return false;
        }
    }

    /**
     * @param $query
     * @return array|false
     */
    public static function executeQuery($query)
    {
        $connection = self::connect();
        $result = $connection->query($query);
        if ($result === false) {
            $message = "Error: " . $connection->error;
            return [
                false,
                'message'=>$message
            ];
        }

        return $result;
    }
}