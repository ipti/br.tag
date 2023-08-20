<?php

class GetEscolasFromSEDUseCase
{
    /**
     * Summary of exec
     * @param InEscola $inEscola
     * @throws SedspException
     */
    function exec(InEscola $inEscola)
    {
        $schoolSEDDataSource = new SchoolSEDDataSource();
        $response = $schoolSEDDataSource->getSchool($inEscola);

        $inepId = '35' . $response->getOutEscolas()[0]->getOutCodEscola();
        $schoolModel = SchoolIdentification::model()->find('inep_id = :inep_id', [':inep_id' => $inepId]);

        if ($schoolModel !== null) {
            CVarDumper::dump('Escola já cadastrada no TAG', 10, true);
            return;
        }

        $transaction = Yii::app()->db->beginTransaction();
        try {
            $mapper = (object) SchoolMapper::parseToTAGSchool($response);
            $schoolIdentification = new SchoolIdentification();
            $schoolIdentification->attributes = $mapper->SchoolIdentification->getAttributes();

            if ($schoolIdentification->validate() && $schoolIdentification->save())
                CVarDumper::dump('Escola salva com sucesso no TAG.', 10, true);
            else 
                throw new SedspException('Não foi possível salvar a escola no banco de dados.');
            
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollback();
        }
    }
}