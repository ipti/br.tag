<?php

class GetEscolasFromSEDUseCase
{
    public function exec(InEscola $inEscola)
    {
        $result = $this->fetchSchoolData($inEscola);
        $schoolId = $this->buildSchoolId($result);


        if ($this->existSchool($inEscola)) {
            $inRelacaoClasses = $this->getClassesFromSED($schoolId);
            return $this->getSchoolClasses($inRelacaoClasses);
        } else {
            if ($this->createSchool($inEscola)) {
                $inRelacaoClasses = $this->getClassesFromSED($schoolId);
                return $this->getSchoolClasses($inRelacaoClasses);
            } else {
                throw new SedspException('Não foi possível salvar a escola no banco de dados.');
            }
        }
    }

    public function existSchool(InEscola $inEscola)
    {
        $result = $this->fetchSchoolData($inEscola);
        $schoolId = $this->buildSchoolId($result);
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

    public function buildSchoolId($schoolData)
    {
        $sed_inep_id = $schoolData->getOutEscolas()[0]->getOutCodEscola();

        return SchoolMapper::mapInepId($sed_inep_id);
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

        return ($school->validate() && $school->save()) ? true : false;
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