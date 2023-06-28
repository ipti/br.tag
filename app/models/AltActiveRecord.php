<?php

/**
 * Created by PhpStorm.
 * User: RuanNascimentoSantos
 * Date: 23/11/2016
 * Time: 13:37
 */
abstract class AltActiveRecord extends CActiveRecord
{
    public function setDb2Connection($db2 = false){
        if($db2){
            self::$db=Yii::app()->db;
            if(self::$db instanceof CDbConnection) {
                self::$db->setActive(true);
                return self::$db;
            }
        }else{
            self::$db=Yii::app()->db;
            self::$db->setActive(true);
            return self::$db;
        }   
    }
}