<?php

namespace src\assets\php\modules\users\controllers;

require_once '../BaseController.php';

use \src\assets\php\BaseController;
use \src\assets\php\modules\users\models\Users;

//Default controller with CRUD actions to users table
class DefaultController extends BaseController
{
    /**
     * @return void
     */
    public function actionIndex()
    {
        $users = new users();
        self::sendSuccess($users->findAll());
    }

    /**
     * @param $id
     * @return void
     */
    public function actionView($id)
    {
        $users = new users();
        $results = $users->findOne($id);
        if($results->num_rows > 0)
            self::sendSuccess($results);
        else
            self::sendError('User not found');
    }

    /**
     * @return string[]|true|true[]
     */
    public function actionCreate()
    {
        $users = new users();
        $params = self::getParams();;
        $time = time();
        $params['created_at'] = $time;
        $params['updated_at'] = $time;

        if (!$users->validate($params))
            return ['error' => true];
        self::sendSuccess($users->insert($params));
    }

    /**
     * @param $id
     * @return false|void
     */
    public function actionUpdate($id)
    {
        $user = new users();
        $params = self::getParams();
        $time = time();
        $params['updated_at'] = $time;

        $entry = $user->findOne($id);
        if (!$entry)
            return false;

        if (!$user->validate($params))
            return false;

        self::sendSuccess($user->update($params, $id));
    }

    /**
     * @param $id
     * @return void
     */
    public function actionDelete($id)
    {
        $user = new Users();
        self::sendSuccess($user->delete($id));
    }
}
