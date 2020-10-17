<?php

namespace app\models;

use Yii;

class Asignatura extends \yii\db\ActiveRecord
{
     
    //private $primaryKey = 'codio_pla';

    public static function tableName()
    {
        return 'asignatura';
    }

    public static function getDb()
    {
      
        return Yii::$app->upes_db;  
     
    }
    
    public static function primaryKey(){
        return ['codigo'];
    }

   
}