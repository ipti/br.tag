<?php

Yii::import('application.modules.sedsp.models.*');
Yii::import('application.modules.sedsp.datasources.sed.TipoEnsinoSEDDataSource');
class ClassroomMapper
{
    public static function parseToTAGFormacaoClasse($content)
    {
        $response = json_decode($content);
        $result = [];

        $tipos_datasource =  new TipoEnsinoSEDDataSource();

        $tipos_ensino = $tipos_datasource->getTipos();

        $outClasse = OutClasse::fromJson($response);
        $outAlunos = $outClasse->getOutAlunos();
        $tempo_inicio = explode(":", $outClasse->outHorarioInicio);
        $tempo_fim = explode(":", $outClasse->outHorarioFim);

        $classroom_tag = new Classroom($response);
        
        $stage = ClassroomMapper::convertTipoEnsinoToStage($outClasse->outCodTipoEnsino, $outClasse->outCodSerieAno);
        
        $serie = ClassroomMapper::getSerieFromClasse($outClasse, $tipos_ensino);
        
        // Classroom
        $classroom_tag->inep_id = $outClasse->outNumClasse;
        $classroom_tag->name = $serie." ".$outClasse->outTurma;
        $classroom_tag->edcenso_stage_vs_modality_fk = $stage;
        $classroom_tag->assistance_type = 0;
        $classroom_tag->modality = 1;
        $classroom_tag->school_inep_fk = Yii::app()->user->school;
        $classroom_tag->initial_hour = $tempo_inicio[0];
        $classroom_tag->initial_minute = $tempo_inicio[1];
        $classroom_tag->final_hour = $tempo_fim[0];
        $classroom_tag->final_minute = $tempo_fim[1];
        $classroom_tag->week_days_sunday = 0;
        $classroom_tag->week_days_monday = 1;
        $classroom_tag->week_days_tuesday = 1;
        $classroom_tag->week_days_wednesday = 1;
        $classroom_tag->week_days_thursday = 1;
        $classroom_tag->week_days_friday = 1;
        $classroom_tag->week_days_saturday = 1;
        $classroom_tag->school_year = Yii::app()->user->year;
        $classroom_tag->pedagogical_mediation_type = 1;

        $result["Classroom"] = $classroom_tag;
        $result["Students"] = $outAlunos;

        return $result;
    }



    /**
     * Summary of convertTipoEnsinoToStage
     * @param string $tipo_ensino
     * @param string $serie
     * @throws \Exception
     * @return int
     */
    private static function convertTipoEnsinoToStage($tipo_ensino, $serie){
        $mapper_tipo_ensino = [
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

        if(isset($mapper_tipo_ensino[$tipo_ensino][$serie])){
            return $mapper_tipo_ensino[$tipo_ensino][$serie];
        }

        throw new Exception("Tipo de ensino não tem etapa equivalente no mapa de conversão", 1);
        
    }

    /**
     * Summary of getSerieFromClasse
     * @param OutClasse $outClasse
     * @param OutListaTiposEnsino $tipos_ensino
     * @return OutSerieAno
     */
    private static function getSerieFromClasse($outClasse, $tipos_ensino) {
        $tipo_ensino_index = array_search($outClasse->getOutCodTipoEnsino(), array_column($tipos_ensino->getOutTipoEnsino(), "outCodTipoEnsino"));  
        $tipo = $tipos_ensino->getOutTipoEnsino()[$tipo_ensino_index];
        $serie_index = array_search($outClasse->getOutCodSerieAno(), array_column($tipo->getOutSerieAno(), "outCodSerieAno"));
        $serie = $tipo->getOutSerieAno()[$serie_index];

        return $serie;

    }
}
