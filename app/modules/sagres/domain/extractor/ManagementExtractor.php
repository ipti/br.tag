<?php

use SagresEdu\CabecalhoTType;


class ManagementExtractor
{


    public function getManagementId()
    {
        $query = "SELECT id, cod_unidade_gestora FROM provision_accounts";

        try {
            $managementUnitCode = Yii::app()->db->createCommand($query)->queryRow();
        } catch (PDOException $e) {
            throw new Exception('Erro ao buscar o código da unidade gestora: ' . $e->getMessage());
        }

        return (int) $managementUnitCode['id'];
    }


    /**
     * @return Classroom[]
     */
    public function execute($managementId)
    {

        return ProvisionAccounts::model()->find(array(
            'select' => 'id , cod_unidade_gestora , name_unidade_gestora, cpf_responsavel, cpf_gestor',
            'condition' => 'id = :managementUnitId',
            'params' => array(':managementUnitId' => $managementId),
        ));


    }





}





?>