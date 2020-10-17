<?php

namespace app\models;

use Yii;

class Notash extends \yii\db\ActiveRecord
{
     
    public static function tableName()
    {
        return 'notash'; 
    }

    public static function getDb()
    {
      
        return Yii::$app->upes_db;  

    }
      
}