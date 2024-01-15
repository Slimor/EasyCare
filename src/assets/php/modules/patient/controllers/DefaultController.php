<?php
namespace src\assets\php\modules\patient\controllers;

require_once '../BaseController.php';

use \src\assets\php\BaseController;
use \src\assets\php\modules\patient\models\Patients;

//Default controller with CRUD actions to patients table
class DefaultController extends BaseController
{
    /**
     * @return array|null
     */
    public function actionIndex()
    {
        $patients = new Patients();
        BaseController::sendSuccess($patients->findAll());
    }

    /**
     * @param $id
     * @return void
     */
    public function actionView($id)
    {
        $patients = new Patients();
        $results = $patients->findOne($id);

        if($results)
            BaseController::sendSuccess($results);
        else
            BaseController::sendError("Patient with id: ".$id." not found.");
    }

    /**
     * @return true[]|void
     */
    public function actionCreate()
    {
        $patients = new Patients();
        $params = self::getParams();;

        if (!$patients->validate($params))
            return ['error' => true];

        $result = $patients->insert($params);
        BaseController::sendSuccess($result,'Successfully created new Patient');
    }

    /**
     * @param $id
     * @return bool|string[]|true[]
     */
    public function actionUpdate($id)
    {
        $patient = new Patients();
        $params = self::getParams();

        $entry = $patient->findOne($id);
        if (!$entry)
            return false;

        if (!$patient->validate($params))
            return false;

        $result = $patient->update($params, $id);
        BaseController::sendSuccess($result,'Successfully created new Patient');
    }

    /**
     * @param $id
     * @return void
     */
    public function actionDelete($id)
    {
        $patient = new Patients();
        BaseController::sendSuccess($patient->delete($id),'Successfully deleted Patient');
    }
}
