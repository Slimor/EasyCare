<?php
namespace src\assets\php\modules\patient\models;

require_once '../Base.php';

use \src\assets\php\Base;


/**
 * This is model for table 'patients'
 *
 * @property int $id
 * @property string $Name
 * @property string $Surname
 * @property string $Birth_date
 * @property string $City
 * @property string $Street
 * @property string $Post_code
 * @property string $Blood_type
 * @property int $PESEL
 * @property int $Phone
 * @property string $Email
 * @property int $Created_at
 * @property int $Updated_at
 */
class Patients extends Base
{

    /**
     * @return string
     */
    public static function tableName()
    {
        return 'patients';
    }

    /**
     * @return bool
     */
    public function findAll()
    {
        $query = 'SELECT * FROM '.self::tableName();
        $results = self::executeQuery($query);
        return self::returnQueryResults($results);
    }

    /**
     * @param $id
     * @return bool
     */
    public function findOne($id)
    {
        $query = 'SELECT * FROM '.self::tableName().' WHERE id = ' . $id;
        $results = self::executeQuery($query);
        return self::returnQueryResults($results);
    }

    /**
     * @param $params
     * @return string[]|true|true[]
     */
    public function insert($params)
    {
        if (!self::validate($params))
            return ['error' => true];

        $columns = implode(', ', array_keys($params));
        $values = implode(', ', array_fill(0, count($params), '?'));

        $query = "INSERT INTO `".self::tableName()."` ($columns) VALUES ($values)";
        $connection = self::connect();

        $stmt = $connection->prepare($query);
        if ($stmt === false)
            return ['error' => 'Prepare failed: ' . $connection->error];

        $types = '';
        $valuesArray = [];

        foreach ($params as $key=>$value) {
            if (is_int($value)) {
                $types .= 'i'; // 'i' for integer
            } else {
                $types .= 's'; // 's' for string
            }
            $valuesArray[] = &$params[$key];
        }

        array_unshift($valuesArray, $types);
        call_user_func_array([$stmt, 'bind_param'], $valuesArray);

        $result = $stmt->execute();

        if ($result === false)
            return ['error' => 'Execute failed: ' . $stmt->error];

        return true;
    }

    /**
     * @param $params
     * @param $id
     * @return string[]|true|true[]
     */
    public function update($params, $id)
    {
        if (!self::validate($params))
            return ['error' => true];

        $setClauses = [];
        $types = '';
        $valuesArray = [];

        foreach ($params as $key => $value) {
            if (is_int($value)) {
                $types .= 'i'; // 'i' for integer
            } else {
                $types .= 's'; // 's' for string
            }
            $setClauses[] = "`$key` = ?";
            $valuesArray[] = &$params[$key];
        }

        $setClause = implode(', ', $setClauses);

        $query = "UPDATE `".self::tableName()."` SET $setClause WHERE `id` = ?";

        // Add the id parameter to the end of the values array
        $types .= 'i'; // Assuming id is an integer
        $valuesArray[] = &$id;

        $connection = self::connect();

        $stmt = $connection->prepare($query);
        if ($stmt === false)
            return ['error' => 'Prepare failed: ' . $connection->error];

        // Ensure all parameters are references
        $refs = [];
        foreach ($valuesArray as $key => $value) {
            $refs[$key] = &$valuesArray[$key];
        }

        array_unshift($refs, $types);
        call_user_func_array([$stmt, 'bind_param'], $refs);

        $result = $stmt->execute();

        if ($result === false)
            return ['error' => 'Execute failed: ' . $stmt->error];

        return true;
    }

    /**
     * @param $id
     * @return array|false
     */
    public function delete($id)
    {
        $query = 'DELETE FROM '.self::tableName().' WHERE id = ' . $id;
        $results = self::executeQuery($query);
        return self::returnQueryResults($results);
    }

    //TODO Validacja
    public function validate($params)
    {
        return true;

        return false;
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