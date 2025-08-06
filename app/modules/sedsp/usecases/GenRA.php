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
     * Summary of exec.
     * @param int $tag_student_id StudentIdentificantion Id from TAG
     * @param bool $force Force search from TAG
     * @return DadosAluno
     */
    public function exec($id, $force = false)
    {
        $ucidentify = new IdentifyStudentRACode();
        $TryRA = $ucidentify->exec($id, $force);

        if (method_exists($TryRA, 'getoutSucesso')) {
            if (!isset($TryRA->outErro)) {
                $ucadd = new AddRACodeToTAG();
                $student = $ucadd->exec($id, $TryRA->getoutAluno()->outNumRA);

                return $student->gov_id;
            } else {
                if ($force) {
                    throw new Exception('RA Não Encontrado');
                } else {
                    return false;
                }
            }
        } elseif ($TryRA->getHasResponse()) {
            if (400 == $TryRA->getCode()) {
                throw new Exception($TryRA->getoutErro());
            } elseif (500 == $TryRA->getCode()) {
                throw new Exception('Erro 500');
            }
        }

        return 'Tentar Novamente';
    }
}
