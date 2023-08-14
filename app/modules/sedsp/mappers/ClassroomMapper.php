<?php

class ClassroomMapper
{
    public static function parseToTAGFormacaoClasse($outFormacaoClasse)
    {    
        $basicDataSEDDataSource = new BasicDataSEDDataSource();
        $tiposEnsino = $basicDataSEDDataSource->getTipoEnsino();
        $stage = ClassroomMapper::convertTipoEnsinoToStage($outFormacaoClasse->getOutCodTipoEnsino(), $outFormacaoClasse->getOutCodSerieAno());
        $serie = ClassroomMapper::getNameSerieFromClasse($outFormacaoClasse, $tiposEnsino);

        $classroomTag = new Classroom($outFormacaoClasse);
        $classroomTag->inep_id = $outFormacaoClasse->outNumClasse;
        $classroomTag->name = $serie->getOutDescTipoEnsino()." ".$outFormacaoClasse->outTurma;
        $classroomTag->edcenso_stage_vs_modality_fk = $stage;
        $classroomTag->schooling = 1;
        $classroomTag->assistance_type = 0;
        $classroomTag->modality = 1;
        $classroomTag->school_inep_fk = Yii::app()->user->school;
        $classroomTag->initial_hour = substr($outFormacaoClasse->outHorarioInicio, 0, 2);
        $classroomTag->initial_minute = substr($outFormacaoClasse->outHorarioInicio, -2);
        $classroomTag->final_hour = substr($outFormacaoClasse->outHorarioFim, 0, 2);
        $classroomTag->final_minute = substr($outFormacaoClasse->outHorarioFim, -2);
        $classroomTag->week_days_sunday = 0;
        $classroomTag->week_days_monday = 1;
        $classroomTag->week_days_tuesday = 1;
        $classroomTag->week_days_wednesday = 1;
        $classroomTag->week_days_thursday = 1;
        $classroomTag->week_days_friday = 1;
        $classroomTag->week_days_saturday = 1;
        $classroomTag->school_year = Yii::app()->user->year;
        $classroomTag->pedagogical_mediation_type = 1;

        $parseResult = [];
        $parseResult["Classroom"] = $classroomTag;
        $parseResult["Students"] = $outFormacaoClasse->getOutAlunos();
        
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
