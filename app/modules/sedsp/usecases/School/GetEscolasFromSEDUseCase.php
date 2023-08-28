<?php

class GetEscolasFromSEDUseCase
{
    function exec(InEscola $inEscola)
    {      
        $result = $this->fetchSchoolData($inEscola);

        $schoolId  = $this->buildSchoolId($result);
        $schoolModel = $this->findSchoolById($schoolId);
        
        $mapper = (object) SchoolMapper::parseToTAGSchool($result);


        if ($schoolModel->inep_id !== null) {  
            $inRelacaoClasses = $this->getClassesFromSED($schoolId);        
            $this->getSchoolClasses($inRelacaoClasses);

            return true;
        } else {
            $schoolAttributes = $mapper->SchoolIdentification->getAttributes();
            $createdSchool = $this->createAndSaveNewSchool($schoolAttributes);
            
            if ($createdSchool) {    
                $inRelacaoClasses = $this->getClassesFromSED($schoolId); 
                $this->getSchoolClasses($inRelacaoClasses);

                return true;
            } else {
                throw new SedspException('Não foi possível salvar a escola no banco de dados.');
            }
        }
    } 

    function buildSchoolId($schoolData)
    {
        return '35' . $schoolData->getOutEscolas()[0]->getOutCodEscola();
    }

    function fetchSchoolData(InEscola $inEscola)
    {
        $dataSource = new SchoolSEDDataSource();
        return $dataSource->getSchool($inEscola);
    }

    private function findSchoolById($schoolId)
    {
        return SchoolIdentification::model()->find('inep_id = :inep_id', [':inep_id' => $schoolId]);
    }

    function getClassesFromSED($schoolId)
    {
        $inAnoLetivo = Yii::app()->user->year;
        $inCodEscola = $this->extractStateCode($schoolId); 
        return new InRelacaoClasses($inAnoLetivo, $inCodEscola, null, null, null, null);
    }

    function createAndSaveNewSchool($schoolAttributes)
    {
        $school = new SchoolIdentification();
        $school->attributes = $schoolAttributes;
        
        return ($school->validate() && $school->save()) ? true : false;
    }

    function extractStateCode($schoolId)
    {
        // remove os 2 dígitos iniciais do código da escola, referente ao código do estado
        return substr($schoolId, 2);
    }

    function getSchoolClasses(InRelacaoClasses $inRelacaoClasses)
    {
        $classes = new GetRelacaoClassesFromSEDUseCase();
        $classes->exec($inRelacaoClasses);
    }
}