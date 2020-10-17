<?php
namespace app\models;

use Yii;

class Carrera extends \yii\db\ActiveRecord
{
     
    public static function tableName()
    {
        return 'carrera';
    }

    public static function getDb()
    {
      
        return Yii::$app->upes_db;  

    }

    public static function primaryKey(){
        return ['codigo_car'];
    }
   
}