<?php
namespace src\assets\php\modules\todo\controllers;


use \src\assets\php\BaseController;
use \src\assets\php\modules\todo\models\Todo;

//Default controller with CRUD actions to users table
class DefaultController extends BaseController
{
    /**
     * @return bool
     */
    public function actionIndex()
    {
        $todo = new Todo();
        BaseController::sendSuccess($todo->findAll());
    }

    /**
     * @param $id
     * @return bool
     */
    public function actionView($id)
    {
        $todo = new Todo();
        $result = $todo->findOne($id);
        BaseController::sendSuccess($result);
    }

    /**
     * @return string[]|true|true[]
     */
    public function actionCreate()
    {
        $todo = new Todo();
        $params = self::getParams();;

        if (!$todo->validate($params))
            return ['error' => true];

        $result = $todo->insert($params);
        BaseController::sendSuccess($result,'Successfully created new todo task');
    }

    /**
     * @param $id
     * @return bool|string[]|true[]
     */
    public function actionUpdate($id)
    {
        $todo = new Todo();
        $params = self::getParams();

        $entry = $todo->findOne($id);
        if (!$entry)
            return false;

        if (!$todo->validate($params))
            return false;

        $result = $todo->update($params, $id);
        BaseController::sendSuccess($result,'Successfully updated todo task');
    }

    /**
     * @param $id
     * @return bool
     */
    public function actionDelete($id)
    {
        $todo = new Todo();
        $result = $todo->delete($id);
        BaseController::sendSuccess($result,'Successfully deleted todo task');
    }
}
