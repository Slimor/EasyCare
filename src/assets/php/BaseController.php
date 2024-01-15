<?php

namespace src\assets\php;

class BaseController
{
    /**
     * @return mixed
     */
    public static function getParams()
    {
        if ($_SERVER['CONTENT_TYPE'] === 'application/json') {
            // JSON data
            $rawData = file_get_contents("php://input");
            $cleanedData = str_replace(["\r", "\n"], '', $rawData);
            return json_decode($cleanedData, true);
        } else {
            // Form data
            return $_POST;
        }
    }

    /**
     * @param $statusCode
     * @param $data
     * @return void
     */
    public static function sendResponse($statusCode, $data) {
        http_response_code($statusCode);
        echo json_encode($data);
        exit();
    }

    /**
     * @param $data
     * @param $message
     * @return void
     */
    public static function sendSuccess($data,$message = 'Ok.')
    {
        self::sendResponse(200, ['success' => true, 'message' => $message,'data'=>$data]);
    }

    /**
     * @param $message
     * @param $code
     * @return void
     */
    public static function sendError($message = 'Error.',$code = 202)
    {
        self::sendResponse($code, ['success' => false, 'message' => $message]);
    }
}