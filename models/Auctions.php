<?php

namespace app\models;

use Yii;



/**
 * This is the model class for table "auctions".
 *
 * @property int $id
 * @property string $key_word
 */
class Auctions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'auctions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['number'], 'string', 'max' => 255],
            [['key_word'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'a_number' => 'Number',
        ];
    }
}
