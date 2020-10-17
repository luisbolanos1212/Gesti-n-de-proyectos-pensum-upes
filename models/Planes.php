<?php

namespace app\models;

use Yii;

class Planes extends \yii\db\ActiveRecord
{
     
    //private $primaryKey = 'codio_pla';

    public static function tableName()
    {
        return 'planes';
    }

    public static function getDb()
    {
      
        return Yii::$app->upes_db;  
     
    }

    
    public static function primaryKey(){
        return ['codigo_pla'];
    }

   
}