<?php


/**
 * @property GradeUnity $unity
 * @property int $enrollmentId
 * @property int $disciplineId
 */
class CheckIfUnityHasAllGradesUsecase
{


    public function __construct($unity, $enrollmentId, $disciplineId)
    {
        $this->unity = $unity;
        $this->enrollmentId = $enrollmentId;
        $this->disciplineId = $disciplineId;
    }
    public function exec()
    {

        $grades = $this->getStudentGradesFromUnity(
            $this->enrollmentId,
            $this->disciplineId,
            $this->unity->id
        );

        $countGrades = count(array_filter(array_column($grades, "grade"), function ($item) {
            return isset($item) && $item != null && $item != "";
        }));

        $countModalities = $this->unity->countGradeUnityModalities;

        return $countModalities == $countGrades;
    }

    private function getStudentGradesFromUnity($enrollmentId, $discipline, $unityId)
    {

        $gradesIds = array_column(Yii::app()->db->createCommand(
            "SELECT
                g.id
                FROM grade g
                join grade_unity_modality gum on g.grade_unity_modality_fk = gum.id
                join grade_unity gu on gu.id= gum.grade_unity_fk
                WHERE g.enrollment_fk = :enrollment_id and g.discipline_fk = :discipline_id and gu.id = :unity_id and gum.type = '" . GradeUnityModality::TYPE_COMMON . "'"
        )->bindParam(":enrollment_id", $enrollmentId)
            ->bindParam(":discipline_id", $discipline)
            ->bindParam(":unity_id", $unityId)->queryAll(), "id");

        if ($gradesIds == null) {
            return [];
        }

        return Grade::model()->findAll(
            array(
                'condition' => 'id IN (' . implode(',', $gradesIds) . ')',
            )
        );
    }
}


