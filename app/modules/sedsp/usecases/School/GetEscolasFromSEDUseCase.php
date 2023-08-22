<?php

class GetEscolasFromSEDUseCase
{

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

        $mapper = (object) SchoolMapper::parseToTAGSchool($response);
        $schoolIdentification = new SchoolIdentification();
        $schoolIdentification->attributes = $mapper->SchoolIdentification->getAttributes();

        if ($schoolIdentification->validate() && $schoolIdentification->save()) {   
            $inAnoLetivo = Yii::app()->user->year;
            $inCodEscola = substr($inepId, 2); // remove os 2 dígitos iniciais do código da escola, referente ao código do estado
            $inRelacaoClasses = new InRelacaoClasses($inAnoLetivo, $inCodEscola, null, null, null, null);
            
            $classes = new GetRelacaoClassesFromSEDUseCase();
            $classes->exec($inRelacaoClasses);

            CVarDumper::dump('Escola '. $mapper->SchoolIdentification->inep_id . ' - ' . $mapper->SchoolIdentification->name . ' salva com sucesso no TAG.', 10, true);
            return true;
        }else {
            CVarDumper::dump($schoolIdentification->getErrors(), 10, true);
            throw new SedspException('Não foi possível salvar a escola no banco de dados.');
        }
    }         
}