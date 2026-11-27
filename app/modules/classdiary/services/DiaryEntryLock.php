<?php

/** Regras de bloqueio compartilhadas pelos pontos de lançamento. */
class DiaryEntryLock
{
    public const GRADE = 'grade';
    public const FREQUENCY = 'frequency';
    public const LESSON = 'lesson';

    public static function isLocked($classroomId, $type)
    {
        if (!in_array($type, [self::GRADE, self::FREQUENCY, self::LESSON], true)) {
            throw new InvalidArgumentException('Tipo de lançamento inválido.');
        }

        $classroom = Classroom::model()->findByPk($classroomId);
        if ($classroom === null) {
            throw new CHttpException(400, 'Turma inválida.');
        }

        $result = Yii::app()->db->createCommand(
            'SELECT locked FROM diary_entry_lock_year WHERE school_inep_fk = :school AND school_year = :year AND entry_type = :type '
            . 'AND classroom_fk IN (0, :classroom) ORDER BY classroom_fk DESC LIMIT 1'
        )->queryScalar([
            ':school' => $classroom->school_inep_fk,
            ':year' => $classroom->school_year,
            ':type' => $type,
            ':classroom' => $classroom->id,
        ]);

        return $result !== false && (int) $result === 1;
    }

    public static function assertUnlocked($classroomId, $type)
    {
        if (self::isLocked($classroomId, $type)) {
            throw new CHttpException(403, 'Este lançamento está bloqueado para a turma. Procure a administração.');
        }
    }

    /** Never trust the classroom supplied alongside enrollment or grade IDs. */
    public static function assertStudentsBelongToClassroom($classroomId, $students)
    {
        if (!is_array($students)) {
            throw new CHttpException(400, 'Lista de alunos inválida.');
        }
        foreach ($students as $student) {
            $enrollment = isset($student['enrollmentId']) ? StudentEnrollment::model()->findByPk($student['enrollmentId']) : null;
            if ($enrollment === null || (string) $enrollment->classroom_fk !== (string) $classroomId) {
                throw new CHttpException(400, 'Matrícula não pertence à turma selecionada.');
            }
            foreach (['grades', 'partialRecoveriesGrades'] as $key) {
                foreach (($student[$key] ?? []) as $grade) {
                    if (!empty($grade['id'])) {
                        $existing = Grade::model()->findByPk($grade['id']);
                        if ($existing !== null && (string) $existing->enrollment_fk !== (string) $enrollment->id) {
                            throw new CHttpException(400, 'Nota não pertence à matrícula selecionada.');
                        }
                    }
                }
            }
        }
    }

    /** Report cards save three independently controlled categories together. */
    public static function assertReportCardChanges($classroomId, $students, $discipline, $concept, $hasRecovery, $hasFinalConcept)
    {
        self::assertStudentsBelongToClassroom($classroomId, $students);
        $locked = [
            self::GRADE => self::isLocked($classroomId, self::GRADE),
            self::FREQUENCY => self::isLocked($classroomId, self::FREQUENCY),
            self::LESSON => self::isLocked($classroomId, self::LESSON),
        ];
        foreach ($students as $student) {
            $result = GradeResults::model()->findByAttributes(['enrollment_fk' => $student['enrollmentId'], 'discipline_fk' => $discipline]);
            foreach ($student['grades'] as $key => $grade) {
                $index = $key + 1;
                $checks = [
                    self::GRADE => [$concept ? 'grade_concept_' . $index : 'grade_' . $index, $grade['value']],
                    self::FREQUENCY => ['grade_faults_' . $index, $grade['faults']],
                    self::LESSON => ['given_classes_' . $index, $grade['givenClasses']],
                ];
                foreach ($checks as $type => $check) {
                    if ($locked[$type] && !self::sameValue($result === null ? null : $result->{$check[0]}, $check[1])) {
                        throw new CHttpException(403, 'Um lançamento está bloqueado para a turma. Procure a administração.');
                    }
                }
            }
            foreach (['recFinal' => ['rec_final', $hasRecovery], 'finalConcept' => ['final_concept', $hasFinalConcept]] as $key => $check) {
                if ($check[1] && $locked[self::GRADE] && !self::sameValue($result === null ? null : $result->{$check[0]}, $student[$key])) {
                    throw new CHttpException(403, 'As notas estão bloqueadas para a turma. Procure a administração.');
                }
            }
        }
    }

    private static function sameValue($a, $b)
    {
        if (($a === null || $a === '') && ($b === null || $b === '')) {
            return true;
        }
        if (is_numeric($a) && is_numeric($b)) {
            return (float) $a === (float) $b;
        }
        return (string) $a === (string) $b;
    }
}
