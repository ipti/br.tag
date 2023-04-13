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
     * @return DadosAluno
     */
    public function exec($id,$force = false)
    {
        $ucidentify = new IdentifyStudentRACode();
        $TryRA = $ucidentify->exec($id,$force);

        if(method_exists($TryRA,'getoutSucesso')){
            if(!isset($TryRA->outErro)){
                $ucadd = new AddRACodeToTAG();
                $student = $ucadd->exec($id,$TryRA->getoutAluno()->outNumRA);
                return $student->gov_id;
            }else {
                if($force){
                    throw new Exception('RA Não Encontrado');
                }else {
                    return false;
                }
            }
        }else if($TryRA->getHasResponse()){
            if($TryRA->getCode()==400){
                throw new Exception($TryRA->getoutErro());
            }else if($TryRA->getCode()==500){
                throw new Exception('Erro 500');
            }
        }
            return 'Tentar Novamente';
    }

}
