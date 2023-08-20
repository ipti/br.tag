<?php

class ClassroomMapper
{
    /**
     * Summary of parseToTAGFormacaoClasse
     * @param OutFormacaoClasse $outFormacaoClasse
     * @return array<Classroom|StudentIdentification[]>
     */
    public static function parseToTAGFormacaoClasse(OutFormacaoClasse $outFormacaoClasse)
    {    
        $basicDataSEDDataSource = new BasicDataSEDDataSource();
        $tiposEnsino = $basicDataSEDDataSource->getTipoEnsino();
        $stage = ClassroomMapper::convertTipoEnsinoToStage($outFormacaoClasse->getOutCodTipoEnsino(), $outFormacaoClasse->getOutCodSerieAno());
        $serieName = ClassroomMapper::getNameSerieFromClasse($outFormacaoClasse, $tiposEnsino);

        $classroomTag = new Classroom();
        $classroomTag->school_inep_fk = '35' . $outFormacaoClasse->getOutCodEscola();
        $classroomTag->inep_id = $outFormacaoClasse->getOutNumClasse();
        $classroomTag->name = $serieName->getOutDescTipoEnsino()." ".$outFormacaoClasse->getOutTurma();
        $classroomTag->edcenso_stage_vs_modality_fk = $stage;
        $classroomTag->schooling = 1;
        $classroomTag->assistance_type = 0;
        $classroomTag->modality = 1;
        $classroomTag->initial_hour = substr($outFormacaoClasse->getOutHorarioInicio(), 0, 2);
        $classroomTag->initial_minute = substr($outFormacaoClasse->getOutHorarioInicio(), -2);
        $classroomTag->final_hour = substr($outFormacaoClasse->getOutHorarioFim(), 0, 2);
        $classroomTag->final_minute = substr($outFormacaoClasse->getOutHorarioFim(), -2);
        $classroomTag->week_days_sunday = 0;
        $classroomTag->week_days_monday = 1;
        $classroomTag->week_days_tuesday = 1;
        $classroomTag->week_days_wednesday = 1;
        $classroomTag->week_days_thursday = 1;
        $classroomTag->week_days_friday = 1;
        $classroomTag->week_days_saturday = 1;
        $classroomTag->school_year = Yii::app()->user->year;
        $classroomTag->pedagogical_mediation_type = 1;
    
        $indexedByAcronym = [];
		$edcensoUf = EdcensoUf::model()->findAll();
		foreach ($edcensoUf as $uf) {
			$indexedByAcronym[$uf['acronym']] = $uf;
		}
    
        $studentDatasource = new StudentSEDDataSource();
        $listStudents = [];
        $students = $outFormacaoClasse->getOutAlunos();
        foreach ($students as $student) {
            $studentIdentification = new StudentIdentification();
            $studentIdentification->inep_id = $student->getOutNumRa();
            $studentIdentification->name = $student->getOutNomeAluno();
            $studentIdentification->birthday = $student->getOutDataNascimento();         
           
            $outExibirFichaAluno = $studentDatasource->exibirFichaAluno(new InAluno($student->getOutNumRa(), $student->getOutDigitoRA(), "SP"))->getOutDadosPessoais();

            $studentIdentification->sex = $outExibirFichaAluno->getOutCodSexo();
            $studentIdentification->color_race = $outExibirFichaAluno->getOutCorRaca();
            $studentIdentification->filiation = 1;
            $studentIdentification->filiation_1 = $outExibirFichaAluno->getOutNomeMae();
            $studentIdentification->filiation_2 = $outExibirFichaAluno->getOutNomePai();
            $studentIdentification->nationality = $outExibirFichaAluno->getOutNacionalidade();
            $studentIdentification->edcenso_nation_fk = $outExibirFichaAluno->getOutCodPaisOrigem();
            $studentIdentification->edcenso_uf_fk = intval($indexedByAcronym[$outExibirFichaAluno->getOutSiglaUfra()]->id);
            $studentIdentification->school_inep_id_fk = $studentIdentification->edcenso_uf_fk . $outFormacaoClasse->getOutCodEscola();
            $studentIdentification->deficiency = 0;
            $studentIdentification->send_year = 2023;
            $studentIdentification->scholarity = $student->getOutSerieNivel();
            
            $listStudents[] = $studentIdentification;
        }

        $parseResult = [];
        $parseResult["Classroom"] = $classroomTag;
        $parseResult["Students"] = $listStudents;

        return $parseResult;
    }



    /**
     * Summary of convertTipoEnsinoToStage
     * @param string $codTipoEnsino
     * @param string $codSerieAno
     * @throws \Exception
     * @return int
     */
    private static function convertTipoEnsinoToStage($codTipoEnsino, $codSerieAno){
        $mapperTipoEnsino = [
            "6" => [
                "1" => 2,
                "2" => 2,
                "4" => 1,
                "5" => 1,
                "6" => 1,
                "7" => 1,
                "0" => 3    
            ],  
            "14" => [
                "1" => 14,
                "2" => 15,
                "3" => 16,
                "4" => 17,
                "5" => 18,
                "6" => 19,
                "7" => 20,
                "8" => 21,
                "9" => 41,
                "0" => 24    
            ],     
        ];

        if(isset($mapperTipoEnsino[$codTipoEnsino][$codSerieAno])){
            return $mapperTipoEnsino[$codTipoEnsino][$codSerieAno];
        }

        throw new Exception("Tipo de ensino não tem etapa equivalente no mapa de conversão", 1);   
    }

    /**
     * Summary of getNameSerieFromClasse
     * @param OutFormacaoClasse $outFormacaoClasse
     * @param OutTiposEnsino $tiposEnsino
     * @return OutTipoEnsino
     */
    private static function getNameSerieFromClasse($outFormacaoClasse, $tiposEnsino) 
    {
        $valueSearch = $outFormacaoClasse->getOutCodTipoEnsino();
        $codTypeEducation = array_column($tiposEnsino->getOutTipoEnsino(), "outCodTipoEnsino");
        $educationTypeIndex = array_search($valueSearch, $codTypeEducation); 

        return $tiposEnsino->getOutTipoEnsino()[$educationTypeIndex];
    }
}
