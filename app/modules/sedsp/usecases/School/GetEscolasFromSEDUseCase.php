<?php

class GetEscolasFromSEDUseCase
{
    public function exec(InEscola $inEscola)
    {
        $result = $this->fetchSchoolData($inEscola);
        $schoolId = $this->buildSchoolId($result->getOutEscolas()[0]->getOutCodEscola());


        if ($this->existSchool($inEscola)) {
            $inRelacaoClasses = $this->getClassesFromSED($schoolId);
            return $this->getSchoolClasses($inRelacaoClasses);
        }

        if ($this->createSchool($inEscola)) {
            $inRelacaoClasses = $this->getClassesFromSED($schoolId);
            return $this->getSchoolClasses($inRelacaoClasses);
        } 
        
        throw new SedspException('Não foi possível salvar a escola no banco de dados.');
    
    }

    public function existSchool(InEscola $inEscola)
    {
        $result = $this->fetchSchoolData($inEscola);
        $schoolId = $this->buildSchoolId($result->getOutEscolas()[0]->getOutCodEscola());
        $schoolModel = $this->findSchoolById($schoolId);

        return ($schoolModel->inep_id !== null) ? true : false;
    }

    public function createSchool(InEscola $inEscola)
    {
        $result = $this->fetchSchoolData($inEscola);
        $mapper = (object) SchoolMapper::parseToTAGSchool($result);
        $schoolAttributes = $mapper->SchoolIdentification->getAttributes();

        return $this->createAndSaveNewSchool($schoolAttributes);
    }

    public function buildSchoolId($sedInepId)
    {
        return SchoolMapper::mapToTAGInepId($sedInepId);
    }

    public function fetchSchoolData(InEscola $inEscola)
    {
        $dataSource = new SchoolSEDDataSource();
        return $dataSource->getSchool($inEscola);
    }

    private function findSchoolById($schoolId)
    {
        return SchoolIdentification::model()->find('inep_id = :inep_id', [':inep_id' => $schoolId]);
    }

    public function getClassesFromSED($schoolId)
    {
        $inAnoLetivo = Yii::app()->user->year;
        $inCodEscola = $this->extractStateCode($schoolId);

        return new InRelacaoClasses($inAnoLetivo, $inCodEscola, null, null, null, null);
    }

    public function createAndSaveNewSchool($schoolAttributes)
    {
        $school = new SchoolIdentification();
        $school->attributes = $schoolAttributes;

        if(!$school->validate()){
            throw new SedspException(CJSON::encode([ 
                'data'=> $schoolAttributes,
                'errors' => $school->getErrors()
            ]));
        }

        return $school->save();
    }

    private function extractStateCode($schoolId)
    {
        // remove os 2 dígitos iniciais do código da escola, referente ao código do estado
                
        return strval(intval(substr($schoolId, 2)));
    }

    public function getSchoolClasses(InRelacaoClasses $inRelacaoClasses)
    {
        $classes = new GetRelacaoClassesFromSEDUseCase();
        return $classes->exec($inRelacaoClasses);
    }
}