<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 13.03.2019
 * Time: 15:03
 */

namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;


class StatusController extends Controller
{

    public function actionStatus(){

        echo 'status';

        die();
    }



}