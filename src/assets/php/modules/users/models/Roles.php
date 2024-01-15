<?php
namespace src\assets\php\modules\users\models;

require_once '../Base.php';

use Random\RandomException;

/**
 * This is model for table 'roles'
 *
 * @property int $id
 * @property string $role
 * @property int $weight
 * @property string $description
 */
class Roles extends \src\assets\php\Base
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return 'roles';
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
    public static function findOne($id)
    {
        $query = 'SELECT * FROM '.self::tableName().' WHERE id = ' . $id;
        $results = self::executeQuery($query);
        return self::returnQueryResults($results);
    }

    /**
     * @param $role
     * @return array|false
     */
    public static function findOneByRole($role)
    {
        $query = 'SELECT * FROM '.self::tableName().' WHERE role = ' . $role;
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
                $types .= 'i';
            } else {
                $types .= 's';
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
                $types .= 'i';
            } else {
                $types .= 's';
            }
            $setClauses[] = "`$key` = ?";
            $valuesArray[] = &$params[$key];
        }

        $setClause = implode(', ', $setClauses);

        $query = "UPDATE `".self::tableName()."` SET $setClause WHERE `id` = ?";

        $types .= 'i';
        $valuesArray[] = &$id;

        $connection = self::connect();

        $stmt = $connection->prepare($query);
        if ($stmt === false)
            return ['error' => 'Prepare failed: ' . $connection->error];

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

}