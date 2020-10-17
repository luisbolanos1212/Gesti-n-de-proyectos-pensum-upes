<?php

namespace app\models;

use Yii;

class Notas extends \yii\db\ActiveRecord
{
     
    public static function tableName()
    {
        return 'notas'; 
    }

    public static function getDb()
    {
      
        return Yii::$app->upes_db;  

    }
      
}