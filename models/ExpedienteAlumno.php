<?php

namespace app\models;

use Yii;

class ExpedienteAlumno extends \yii\db\ActiveRecord
{
     
    public static function tableName()
    {
        return 'expedientealumno';
    }

    public static function getDb()
    {
      
        return Yii::$app->upes_db;  

    }

    public static function primaryKey(){
        return ['carnet'];
    }
   
}