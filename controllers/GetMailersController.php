<?php


namespace app\controllers;

use yii\web\Controller;

class GetMailersController extends Controller
{
    public static function getMailers()
    {
        $params = require '/home/valia/www/others/parserIcetrade/config/params.php';
        return $params['smtpMailers'];
    }
}