<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 28.02.2019
 * Time: 15:27
 */

namespace app\models;

use yii\base\Model;




class Product extends Model
{

    public $product;

    public function rules()
    {
        return [
            ['product', 'required'],
            ['product', 'string', 'length'=>[2,255]]
        ];
    }

}