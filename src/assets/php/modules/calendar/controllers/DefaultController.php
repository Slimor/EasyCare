<?php

namespace public_html\src\assets\php\modules\calendar\controllers;

require_once __DIR__ . '/../BaseController.php';
require_once __DIR__ . '/../modules/calendar/models/calendar.php';

use public_html\src\assets\php\BaseController;
use public_html\src\assets\php\modules\calendar\models\Calendar;

//Default controller with CRUD actions to calendar table
class DefaultController extends BaseController
{
    /**
     * @return bool
     */
    public function actionIndex()
    {
        $calendar = new Calendar();
        return $calendar->findAll();
    }

    /**
     * @param $id
     * @return bool
     */
    public function actionView($id)
    {
        $calendar = new Calendar();
        return $calendar->findOne($id);
    }

    /**
     * @return string[]|true|true[]
     */
    public function actionCreate()
    {
        $calendar = new Calendar();
        $params = self::getParams();;

        if (!$calendar->validate($params))
            return ['error' => true];

        return $calendar->insert($params);
    }

    /**
     * @param $id
     * @return bool|string[]|true[]
     */
    public function actionUpdate($id)
    {
        $calendar = new Calendar();
        $params = self::getParams();

        $entry = $calendar->findOne($id);
        if (!$entry)
            return false;

        if (!$calendar->validate($params))
            return false;

        return $calendar->update($params, $id);
    }

    /**
     * @param $id
     * @return null
     */
    public function actionDelete($id)
    {
        $calendar = new Calendar();
        return $calendar->delete($id);
    }
}
