<?php

Yii::import('application.modules.sedsp.datasources.sed.*');
Yii::import('application.modules.sedsp.datasources.tag.*');
Yii::import('application.modules.sedsp.models.*');

/**
 * @property StudentTAGDataSource $studentTAGDataSource
 * @property StudentSEDDataSource $studentSEDDataSource
 */
class CreateRA
{
    /**
     * Summary of exec
     * @param int $tag_student_id StudentIdentificantion Id from TAG
     * @param boolean $force Force search from TAG
     * @return DadosAluno
     */
    public function exec($id)
    {
        $ucnewstudent = new AddStudentToSED();
        $RA = $ucnewstudent->exec($id);


        if(method_exists($RA,'getoutSucesso')){
            if(!isset($RA->outErro)){

            }else {

            }
        }else if($RA->getHasResponse()){
            if($RA->getCode()==400){
                return $RA->getoutErro();
            }else if($RA->getCode()==500){
                return 'erro 500';
            }
        }else{
            return 'tentar novamente';
        }
    }

}
