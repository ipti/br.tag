<?php

class GetEscolasFromSEDUseCase
{
    public function exec(InEscola $inEscola)
    {
        $result = $this->fetchSchoolData($inEscola);
        $schoolId = SchoolMapper::mapToTAGInepId($result->getOutEscolas()[0]->getOutCodEscola());

        $mapper = (object) SchoolMapper::parseToTAGSchool($result);
        if ($this->saveSchool($mapper)) {
            $inRelacaoClasses = $this->getClassesFromSED($schoolId);
        } else {
            throw new SedspException('Não foi possível salvar a escola no banco de dados.');
        }

        return $this->getSchoolClasses($inRelacaoClasses);
    }

    public function fetchSchoolData(InEscola $inEscola)
    {
        $dataSource = new SchoolSEDDataSource();
        return $dataSource->getSchool($inEscola);
    }

    public function getClassesFromSED($schoolId)
    {
        $inAnoLetivo = Yii::app()->user->year;
        $inCodEscola = $this->extractStateCode($schoolId);

        return new InRelacaoClasses($inAnoLetivo, $inCodEscola, null, null, null, null);
    }

    /**
     * Summary of createAndSaveNewSchool
     * @param mixed $schoolAttributes
     * @throws \SedspException
     * @return bool
     */
    public function saveSchool($school)
    {
        if(!$school->SchoolIdentification->validate()){
            throw new SedspException(CJSON::encode([
                'data'=> $school->SchoolIdentification->attributes,
                'errors' => $school->SchoolIdentification->getErrors()
            ]));
        }

        $status = $school->SchoolIdentification->save();
        if ($status) {
            foreach ($school->SchoolUnities as $unity) {
                if ($status) {
                    if (!$unity->validate()) {
                        throw new SedspException(CJSON::encode([
                            'data' => $unity->attributes,
                            'errors' => $unity->getErrors()
                        ]));
                    }
                    $status = $unity->save();
                }
            }
        }
        return $status;
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