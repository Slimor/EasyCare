<?php

namespace src\assets\php;

require_once 'modules/users/models/Roles.php';
require_once 'modules/users/models/Users.php';

use \src\assets\php\modules\users\models\Users;
use \src\assets\php\modules\users\models\Roles;

class AuthController
{
    /**
     * @return string|true
     * @throws \Random\RandomException
     */
    public function logIn()
    {
        $params = BaseController::getParams();
        if(!isset($params['password']))
            BaseController::sendError('Login cannot be empty',400);

        if(!isset($params['login']) )
            BaseController::sendError('Login cannot be empty',400);

        $login = $params['login'];
        $password = $params['password'];

        $user = Users::findOneByLogin($login);
        if(!$user)
            BaseController::sendError('User '.$login.' not found.',400);

        $userRoleId = $user['role_id'];
        $role = Roles::findOne($userRoleId);
        if(!$role)
            BaseController::sendError('Role '.$userRoleId.' not found.',400);

        $roleWeight = $role['weight'];

        if (!password_verify($password, $user['Password_hash']))
            BaseController::sendError('Wrong Password',400);

        $token = Users::generateToken($user['Id']);

        BaseController::sendSuccess(['token'=>$token,'role'=>$roleWeight],'Successfully logged in');
    }

    /**
     * @param $role
     * @return bool
     *
     * roles
     * 5 - admin
     * 4 - doctor
     * 3 - nurse
     * 2 -
     * 1 -
     * 0 -
     */
    public static function checkRole($role)
    {
        $token = self::getBearerToken();
        $roleEntry = Roles::findOne($role);
        $currentUser = Users::findOneByToken($token);
        $userRole = Users::getRoleWeigth($currentUser);

        if ((int)$userRole >= (int)$roleEntry['weight'])
            return true;

        return false;
    }

    /**
     * @return true
     */
    public static function isLogged()
    {
        $token = AuthController::getBearerToken();
        if(!$token)
            BaseController::sendError('Unauthorised',401);

        //Check token with database
        $user = Users::findOneByToken($token);
        if(!$user)
            BaseController::sendError('Unauthorised',401);

        //Check token expiration date
        $expDate = $user['expDate'];
        if($expDate < time())
            BaseController::sendError('Unauthorised',401);

        return true;
    }

    /**
     * @return false|string
     */
    public static function getBearerToken()
    {
        $headers = getallheaders();

        if (isset($headers['Authorization'])) {
            $authHeader = $headers['Authorization'];

            if (strpos($authHeader, 'Bearer ') === 0)
                return substr($authHeader, 7);

        }

        return false;
    }

}