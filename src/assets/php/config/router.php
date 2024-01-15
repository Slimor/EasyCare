<?php

namespace src\assets\php\config;
//phpinfo();exit;
error_reporting(E_ALL);
ini_set('display_errors', 1);

//Params
require_once 'params.php';
//Controllers
require_once '../modules/patient/controllers/DefaultController.php';
require_once '../modules/users/controllers/DefaultController.php';
require_once '../modules/todo/controllers/DefaultController.php';
require_once '../BaseController.php';
require_once '../AuthController.php';
//Models
require_once '../modules/patient/models/Patients.php';
require_once '../modules/todo/models/Todo.php';
require_once '../Base.php';

use \src\assets\php\AuthController;
use \src\assets\php\BaseController;
use \src\assets\php\modules\patient\controllers\DefaultController as PatientController;
use \src\assets\php\modules\users\controllers\DefaultController as UserController;
use \src\assets\php\modules\todo\controllers\DefaultController as TodoController;

$params = include('params.php');
define('PARAMS',$params);

$route = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

//Extract numeric part from the end of the route if it is numeric
if ($method === 'GET' || $method === 'PATCH' || $method === 'DELETE') {
    $parts = explode('/', $route);
    $lastPart = end($parts);

    if (is_numeric($lastPart)) {
        $id = $lastPart;
        array_pop($parts);
    }

    $route = implode('/', $parts);
}

//Check if user is logged in
if ($route != '/backend/login') {
    if (!AuthController::isLogged())
        BaseController::sendError('Unauthorized', 401);
}

switch ($route) {
    case '/backend/login':
        if ($method == 'POST') {
            $controller = new AuthController();
            $result = $controller->LogIn();
            BaseController::sendSuccess($result);
        } else {
            header("HTTP/1.0 404 Not Found");
            echo '404 Not Found';
            break;
        }
        break;
    case '/backend/patient':
        $controller = new PatientController();
        if (!AuthController::checkRole(2))  //Access could be different for single actions
            BaseController::sendError('Forbidden', 403);

        switch ($method) {
            case 'GET':
                if (isset($id)) {
                    $result = $controller->actionView($id);
                } else {
                    $result = $controller->actionIndex();
                }
                echo json_encode($result);
                break;
            case 'POST':
                $result = $controller->actionCreate();
                echo json_encode($result);
                break;
            case 'PATCH':
                $result = $controller->actionUpdate($id);
                echo json_encode($result);
                break;
            case 'DELETE':
                $result = $controller->actionDelete($id);
                echo json_encode($result);
                break;
            default:
                header("HTTP/1.0 404 Not Found");
                echo '404 Not Found';
                break;
        }
        break;
    case '/backend/user':

        $controller = new UserController();
        if (!AuthController::checkRole(5))
            BaseController::sendError('Forbidden', 403);

        switch ($method) {
            case 'GET':
                if (isset($id)) {
                    $result = $controller->actionView($id);
                } else {
                    $result = $controller->actionIndex();
                }
                echo json_encode($result);
                break;
            case 'POST':
                $result = $controller->actionCreate();
                echo json_encode($result);
                break;
            case 'PATCH':
                $result = $controller->actionUpdate($id);
                echo json_encode($result);
                break;
            case 'DELETE':
                $result = $controller->actionDelete($id);
                echo json_encode($result);
                break;
            default:
                header("HTTP/1.0 404 Not Found");
                echo '404 Not Found';
                break;
        }
        break;
    case '/backend/todo':
        $controller = new TodoController();
        if (!AuthController::checkRole(5))
            BaseController::sendError('Forbidden', 403);
        switch ($method) {
            case 'GET':
                if (isset($id)) {
                    $result = $controller->actionView($id);
                } else {
                    $result = $controller->actionIndex();
                }
                echo json_encode($result);
                break;
            case 'POST':
                $result = $controller->actionCreate();
                echo json_encode($result);
                break;
            case 'PATCH':
                $result = $controller->actionUpdate($id);
                echo json_encode($result);
                break;
            case 'DELETE':
                $result = $controller->actionDelete($id);
                echo json_encode($result);
                break;
            default:
                header("HTTP/1.0 404 Not Found");
                echo '404 Not Found';
                break;
        }
        break;
    default:
        header("HTTP/1.0 404 Not Found lol");
        echo '404 Not Found lol';
        break;
}