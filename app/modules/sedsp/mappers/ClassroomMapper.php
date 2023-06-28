<?php

Yii::import('application.modules.sedsp.models.*');
class ClassroomMapper
{
    public static function parseToTAGFormacaoClasse($content)
    {
        $response = json_decode($content);
        $result = [];

        $outClasse = $response;
        $outAlunos = $response->outAlunos;
        $tempo_inicio = explode(":", $outClasse->outHorarioInicio);
        $tempo_fim = explode(":", $outClasse->outHorarioFim);


        $classroom_tag = new Classroom($response);
        
        // Classroom
        $classroom_tag->inep_id = $outClasse->outNumClasse;
        $classroom_tag->name = $outClasse->outCodSerieAno."º ANO ".$outClasse->outTurma;
        $classroom_tag->edcenso_stage_vs_modality_fk = 1;
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
}
