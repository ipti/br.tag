<?php
/**
 * @var $this yii\web\View
 * @var SchoolIdentification $model app\models\School
 */

    header('Content-Type: '.$model->logo_file_type);
    if(is_file($model->logo_file_content)){
        print $model->logo_file_content;
    }else {
        var_dump($model->logo_file_content);
    }
    exit();

?>