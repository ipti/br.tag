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

        if(!isset($RA->outErro)){
            $ucadd = new AddRACodeToTAG();
            $student = $ucadd->exec($id, $RA->outAluno->outNumRA);
            return $student->gov_id;
        }else{
            return $RA->outErro;
        }
        var_dump($RA);
        exit;
    }

}
