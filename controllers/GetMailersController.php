<?php


namespace app\controllers;

use yii\web\Controller;

class GetMailersController extends Controller
{
    public static function getMailers()
    {
        $params = require __DIR__ . '/../config/params.php';
        return $params['smtpMailers'];
    }
}