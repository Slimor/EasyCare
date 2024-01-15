<?php
namespace src\assets\php\modules\users\models;

require_once '../Base.php';

use Random\RandomException;

/**
 * This is model for table 'users'
 *
 * @property int $id
 * @property string $Login
 * @property string $Password_hash
 * @property string $Email
 * @property string $Token
 * @property int $expDate
 * @property int $role_id
 * @property int $Created_at
 * @property int $Updated_at
 */
class Users extends \src\assets\php\Base
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return 'users';
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

        $password = self::hashPassword($params['password']);
        $params['password_Hash'] = $password;
        unset($params['password']);
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

        if(isset($params['password'])) {
            $password = self::hashPassword($params['password']);
            $params['password_Hash'] = $password;
            unset($params['password']);
        }

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
     * @return void
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
     * @return false|mixed
     */
    public static function getRoleWeigth($user)
    {
        $role = Roles::findOne($user['role_id']);

        if(!$role)
            return false;

        return $role['weight'];
    }

    /**
     * @param $login
     * @return void
     */
    public static function findOneByLogin($login)
    {
        $query = 'SELECT * FROM '.self::tableName().' WHERE login = ' ."'". $login ."'";
        $results = self::executeQuery($query);
        return self::returnQueryResults($results);
    }

    /**
     * @param $token
     * @return false|mixed
     */
    public static function findOneByToken($token)
    {
        $query = "SELECT * FROM ".self::tableName()." WHERE token = '" . $token."'";
        $results = self::executeQuery($query);
        return self::returnQueryResults($results);
    }

    /**
     * @param int $length
     * @param int $exp
     * @return array|string|string[]
     * @throws RandomException
     */
    public static function generateToken($userId, int $length = 32, int $exp = 24)
    {
        if (function_exists('random_bytes')) {
            $randomBytes = random_bytes($length);
        } else {
            $randomBytes = openssl_random_pseudo_bytes($length);
        }

        $token = base64_encode($randomBytes);
        $token = str_replace(['+', '/', '='], ['-', '_', ''], $token);

        if(self::checkForTokenExistence($token))
            $token = self::generateToken();

        $expDate = strtotime("+$exp hours", time());
        $query = "UPDATE ".self::tableName()." SET token ='".$token."', expDate =".$expDate.' WHERE id='.$userId;
        self::executeQuery($query);

        return $token;
    }

    /**
     * @param $token
     * @return bool
     */
    public static function checkForTokenExistence($token)
    {
        $query = "SELECT COUNT(*) AS count FROM ".self::tableName()." WHERE token = '".$token."'";
        $result = self::executeQuery($query);
        self::returnQueryResults($result);
    }

    /**
     * @param $password
     * @return false|string|null
     */
    public static function hashPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

}