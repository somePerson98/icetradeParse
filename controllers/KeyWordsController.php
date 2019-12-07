<?php


namespace app\controllers;

use yii\base\Controller;


class KeyWordsController extends Controller
{
    public static function getKeyWords()
    {
        $params = require '/home/valia/www/others/parserIcetrade/config/params.php';
        return $params['keyWords'];
    }
}