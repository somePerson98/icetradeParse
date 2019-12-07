<?php


namespace app\controllers;

use yii\base\Controller;


class KeyWordsController extends Controller
{
    public static function getKeyWords()
    {
        $params = require __DIR__ . '/../config/params.php';
        return $params['keyWords'];
    }
}