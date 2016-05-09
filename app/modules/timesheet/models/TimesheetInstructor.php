<?php

/**
 *
 * @property integer $id
 * @property string $name
 *
 * The followings are the available model relations:
 * @property EdcensoStageVsModality[] $stages
 * @property EdcensoDiscipline[] $disciplines
 * @property InstructorDisciplines[] $instructorStages
 * @property InstructorDisciplines[] $instructorDisciplines
 */
class TimesheetInstructor extends CActiveRecord
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'instructor_identification';
    }

    public function relations()
    {
        return [
            'stages' => [
                self::HAS_MANY,
                'EdcensoStageVsModality',
                'instructor_fk',
                'with' => 'instructor_stages',
                'order' => 'stageVsModalityFk.name asc'
            ],
            'instructor_stages' => [
                self::MANY_MANY,
                'InstructorStages',
                'instructor_disciplines(instructor_fk, stage_vs_modality_fk)',
                'order' => 'name asc'
            ],

            'disciplines' => [
                self::HAS_MANY,
                'EdcensoDiscipline',
                'instructor_fk',
                'with' => 'instructor_disciplines',
                'order' => 'disciplineFk.name asc'
            ],
            'instructor_disciplines' => [
                self::MANY_MANY,
                'InstructorDisciplines',
                'instructor_disciplines(instructor_fk, discipline_fk)',
                'order' => 'name asc'
            ],
        ];
    }

    public function getInstructorUnavailabilityCount($initialHour = "00:00", $finalHour = "23:59")
    {
        $Unavailabilities = Unavailability::model()->findAll("instructor_fk = :instructor and (initial_hour >= :initial or final_hour <= :final)",
            [
                ":instructor" => $this->id,
                ":initial" => $initialHour,
                ":final" => $finalHour
            ]);


    }


}