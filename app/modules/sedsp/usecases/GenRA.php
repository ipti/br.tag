<?php

Yii::import('application.modules.sedsp.datasources.sed.*');
Yii::import('application.modules.sedsp.datasources.tag.*');
Yii::import('application.modules.sedsp.models.*');

/**
 * @property StudentTAGDataSource $studentTAGDataSource
 * @property StudentSEDDataSource $studentSEDDataSource
 */
class GenRA
{
    /**
     * Summary of exec
     * @param int $tag_student_id StudentIdentificantion Id from TAG
     * @param boolean $force Force search from TAG
     * @return bool|string
     */
    public function exec($id, $force = false)
    {
        $ucidentify = new IdentifyStudentRACode();
        $try_ra = $ucidentify->exec($id, $force);

        if(method_exists($try_ra, 'getoutSucesso')) {
            if(!isset($try_ra->outErro)) {
                $ucadd = new AddRACodeToTAG();
                $student = $ucadd->exec($id, $try_ra->getoutAluno()->outNumRA);
                return $student->gov_id;
            } else {
                if($force) {
                    return 'criar um novo..';
                } else {
                    return false;
                }
            }
        } elseif($try_ra->getHasResponse()) {
            if($try_ra->getCode() == 400) {
                return $try_ra->getoutErro();
            } elseif($try_ra->getCode() == 500) {
                return 'erro 500';
            }
        }

        return 'Tentar Novamente';
    }

}
