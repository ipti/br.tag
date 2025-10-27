<?php

class StudentService
{
    public function getFrequency($classroomFk, $stageFk, $disciplineFk, $date)
    {
        // Fundamental menor
        $classroom = Classroom::model()->findByPk($classroomFk);
        $isMinorSchooling = $classroom->edcensoStageVsModalityFk->unified_frequency == 1 ? true : Yii::app()->utils->isStageMinorEducation($stageFk);
        if ($isMinorSchooling) {
            $schedule = Schedule::model()->find('classroom_fk = :classroom_fk and day = :day and month = :month and
            unavailable = 0 group by day order by day, schedule', ['classroom_fk' => $classroomFk,
                'day' => DateTime::createFromFormat('d/m/Y', $date)->format('d'),
                'month' => DateTime::createFromFormat('d/m/Y', $date)->format('m')]);
        } else {
            $schedule = Schedule::model()->findAll('classroom_fk = :classroom_fk and day = :day and discipline_fk = :discipline_fk
             and month = :month and unavailable = 0 order by day, schedule', ['classroom_fk' => $classroomFk,
                'day' => DateTime::createFromFormat('d/m/Y', $date)->format('d'), 'discipline_fk' => $disciplineFk,
                'month' => DateTime::createFromFormat('d/m/Y', $date)->format('m')]);
        }

        $criteria = new CDbCriteria();
        $criteria->with = ['studentFk'];
        $criteria->together = true;
        $criteria->order = 'name';
        $enrollments = StudentEnrollment::model()->findAllByAttributes(['classroom_fk' => $classroomFk], $criteria);

        if ($schedule != null) {
            if ($enrollments != null && $isMinorSchooling) {
                $students = [];
                foreach ($enrollments as $enrollment) {
                    $array['studentId'] = $enrollment->student_fk;
                    $array['studentName'] = $enrollment->studentFk->name;
                    $array['status'] = $enrollment->status;
                    $array['statusLabel'] = $enrollment->getCurrentStatus();
                    $classFault = ClassFaults::model()->find('schedule_fk = :schedule_fk and student_fk = :student_fk', ['schedule_fk' => $schedule->id, 'student_fk' => $enrollment->student_fk]);
                    $available = date('Y-m-d') >= Yii::app()->user->year . '-' . str_pad($schedule->month, 2, '0', STR_PAD_LEFT);
                    $valid = $this->verifyStatusEnrollment($enrollment, $schedule);
                    $array['schedule'][$schedule->schedule] = [
                        'available' => $available,
                        'day' => $schedule->day,
                        'schedule' => $schedule->schedule,
                        'fault' => $classFault != null,
                        'justification' => $classFault->justification,
                        'valid' => $valid
                    ];
                    array_push($students, $array);
                }
                return ['valid' => true, 'students' => $students, 'isMinorEducation' => true];
            } elseif ($enrollments != null && !$isMinorSchooling) {
                $students = [];
                foreach ($enrollments as $enrollment) {
                    $array['studentId'] = $enrollment->student_fk;
                    $array['studentName'] = $enrollment->studentFk->name;
                    $array['status'] = $enrollment->status;
                    $array['statusLabel'] = $enrollment->getCurrentStatus();
                    foreach ($schedule as $s) {
                        $classFault = ClassFaults::model()->find('schedule_fk = :schedule_fk and student_fk = :student_fk', ['schedule_fk' => $s->id, 'student_fk' => $enrollment->student_fk]);
                        $available = date('Y-m-d') >= Yii::app()->user->year . '-' . str_pad($s->month, 2, '0', STR_PAD_LEFT);
                        $valid = $this->verifyStatusEnrollment($enrollment, $s);
                        $array['schedule'][$s->schedule] = [
                            'available' => $available,
                            'day' => $s->day,
                            'schedule' => $s->schedule,
                            'fault' => $classFault != null,
                            'justification' => $classFault->justification,
                            'valid' => $valid
                        ];
                    }
                    array_push($students, $array);
                }
                return ['valid' => true, 'students' => $students, 'isMinorEducation' => false];
            } elseif ($enrollments == null) {
                return  ['valid' => false, 'error' => 'Matricule alunos nesta turma para trazer o quadro de frequência.'];
            }
        } else {
            return ['valid' => false, 'error' => 'No quadro de horário da turma, não existe dia letivo para este componente curricular/eixo na data selecionada.'];
        }
    }

    private function verifyStatusEnrollment($enrollment, $schedule)
    {
        $dateFormat = 'd/m/Y';
        $dateFormat2 = 'Y-m-d';

        $date = $this->gerateDate($schedule->day, $schedule->month, $schedule->year, 0);

        $startDate = DateTime::createFromFormat($dateFormat, $enrollment->school_readmission_date);
        $returnDate = DateTime::createFromFormat($dateFormat, $enrollment->class_transfer_date);

        $scheduleDate = date_create_from_format($dateFormat, $date);
        $transferDate = isset($enrollment->transfer_date) ? DateTime::createFromFormat($dateFormat2, $enrollment->transfer_date) : null;

        switch ($enrollment->status) {
            case '2': // TRANSFERIDO
                $result = isset($transferDate) && $scheduleDate <= $transferDate;
                break;
            case '13': // Aluno saiu e retornou
                $result = ($scheduleDate < $startDate && $scheduleDate > $returnDate);
                break;
            case '11': // DEATH
                $result = false;
                break;
            default: // Qualquer outro status
                $result = true;
                break;
        }

        return $result;
    }

    private function gerateDate($day, $month, $year, $usecase)
    {
        switch ($usecase) {
            case 0:
                $day = ($day < 10) ? '0' . $day : $day;
                $month = ($month < 10) ? '0' . $month : $month;
                return $day . '/' . $month . '/' . $year;
            case 1:
                $day = ($day < 10) ? '0' . $day : $day;
                $month = ($month < 10) ? '0' . $month : $month;
                return $day . '-' . $month . '-' . $year;
            default:
                break;
        }
    }

    public function getSechedulesToSaveFrequency($schedule, $studentId, $fault, $stageFk, $date, $classroomId)
    {
        // Fundamental menor
        $classroom = Classroom::model()->findByPk($classroomId);
        $isMinorSchooling = $classroom->edcensoStageVsModalityFk->unified_frequency == 1 ? true : Yii::app()->utils->isStageMinorEducation($stageFk);
        if ($isMinorSchooling) {
            $schedules = Schedule::model()->findAll('classroom_fk = :classroom_fk and day = :day and month = :month', ['classroom_fk' => $classroomId,
                'day' => DateTime::createFromFormat('d/m/Y', $date)->format('d'),
                'month' => DateTime::createFromFormat('d/m/Y', $date)->format('m')]);
            foreach ($schedules as $schedule) {
                $this->saveFrequency($schedule, $studentId, $fault);
            }
        } else {
            $schedule = Schedule::model()->find('classroom_fk = :classroom_fk and day = :day and month = :month and schedule = :schedule', ['classroom_fk' => $classroomId,
                'day' => DateTime::createFromFormat('d/m/Y', $date)->format('d'),
                'month' => DateTime::createFromFormat('d/m/Y', $date)->format('m'),
                'schedule' => $schedule]);
            $this->saveFrequency($schedule, $studentId, $fault);
        }
    }

    private function saveFrequency($schedule, $studentId, $fault)
    {
        if ($fault == '1') {
            $classFault = new ClassFaults();
            $classFault->student_fk = $studentId;
            $classFault->schedule_fk = $schedule->id;
            $classFault->save();
        } else {
            ClassFaults::model()->deleteAll('schedule_fk = :schedule_fk and student_fk = :student_fk', ['schedule_fk' => $schedule->id, 'student_fk' => $studentId]);
        }
    }

    public function saveJustification($studentId, $stageFk, $classroomId, $schedule, $date, $justification)
    {
        // Fundamental menor
        $classroom = Classroom::model()->findByPk($classroomId);
        $isMinorSchooling = $classroom->edcensoStageVsModalityFk->unified_frequency == 1 ? true : Yii::app()->utils->isStageMinorEducation($stageFk);
        if ($isMinorSchooling) {
            $schedules = Schedule::model()->findAll('classroom_fk = :classroom_fk and day = :day and month = :month', ['classroom_fk' => $classroomId,
                'day' => DateTime::createFromFormat('d/m/Y', $date)->format('d'),
                'month' => DateTime::createFromFormat('d/m/Y', $date)->format('m')]);
            foreach ($schedules as $schedule) {
                $classFault = ClassFaults::model()->find('schedule_fk = :schedule_fk and student_fk = :student_fk', ['schedule_fk' => $schedule->id, 'student_fk' => $studentId]);
                $classFault->justification = $justification == '' ? null : $justification;
                $classFault->save();
            }
        } else {
            $schedule = Schedule::model()->find('classroom_fk = :classroom_fk and day = :day and month = :month and schedule = :schedule', ['classroom_fk' => $classroomId,
                'day' => DateTime::createFromFormat('d/m/Y', $date)->format('d'),
                'month' => DateTime::createFromFormat('d/m/Y', $date)->format('m'),
                'schedule' => $schedule]);
            $classFault = ClassFaults::model()->find('schedule_fk = :schedule_fk and student_fk = :student_fk', ['schedule_fk' => $schedule->id, 'student_fk' => $studentId]);
            $classFault->justification = $justification == '' ? null : $justification;
            $classFault->save();
        }
    }

    public function getStudent($studentId)
    {
        return StudentIdentification::model()->findByPk($studentId);
    }

    public function getStudentFault($stageFk, $classroomFk, $disciplineFk, $date, $studentFk, $schedule)
    {
        // Fundamental menor
        $classroom = Classroom::model()->findByPk($classroomFk);
        $isMinorSchooling = $classroom->edcensoStageVsModalityFk->unified_frequency == 1 ? true : Yii::app()->utils->isStageMinorEducation($stageFk);
        if ($isMinorSchooling) {
            $schedule = Schedule::model()->find('classroom_fk = :classroom_fk and day = :day and month = :month and
            unavailable = 0 group by day order by day, schedule', ['classroom_fk' => $classroomFk,
                'day' => DateTime::createFromFormat('d/m/Y', $date)->format('d'),
                'month' => DateTime::createFromFormat('d/m/Y', $date)->format('m')]);
        } else {
            $schedule = Schedule::model()->find('classroom_fk = :classroom_fk and day = :day and discipline_fk = :discipline_fk and schedule = :schedule
             and month = :month and unavailable = 0 order by day, schedule', ['classroom_fk' => $classroomFk,
                'day' => DateTime::createFromFormat('d/m/Y', $date)->format('d'), 'discipline_fk' => $disciplineFk,
                'month' => DateTime::createFromFormat('d/m/Y', $date)->format('m'),  'schedule' => $schedule]);
        }
        return  ClassFaults::model()->find('schedule_fk = :schedule_fk and student_fk = :student_fk', ['schedule_fk' => $schedule->id, 'student_fk' => $studentFk]);
    }

    public function getStudentDiary($stageFk, $classroomFk, $disciplineFk, $date, $studentFk)
    {
        // Fundamental menor
        $classroom = Classroom::model()->findByPk($classroomFk);
        $isMinorSchooling = $classroom->edcensoStageVsModalityFk->unified_frequency == 1 ? true : Yii::app()->utils->isStageMinorEducation($stageFk);
        if ($isMinorSchooling == '1') {
            $schedule = Schedule::model()->find('classroom_fk = :classroom_fk and day = :day and month = :month and unavailable = 0 group by day order by day, schedule', ['classroom_fk' => $classroomFk,
                'day' => DateTime::createFromFormat('d/m/Y', $date)->format('d'),
                'month' => DateTime::createFromFormat('d/m/Y', $date)->format('m')]);
        } else {
            $schedule = Schedule::model()->find(
                'classroom_fk = :classroom_fk and
            day = :day and
            month = :month and
            discipline_fk = :discipline_fk
            and unavailable = 0
            group by day
            order by day, schedule',
                [
                    'classroom_fk' => $classroomFk,
                    'day' => DateTime::createFromFormat('d/m/Y', $date)->format('d'),
                    'month' => DateTime::createFromFormat('d/m/Y', $date)->format('m'),
                    'discipline_fk' => $disciplineFk
                ]
            );
        }
        if (!empty($schedule)) {
            $classDiary_key = array_search($studentFk, array_column($schedule->classDiaries, 'student_fk'));

            if (is_numeric($classDiary_key)) {
                return $schedule->classDiaries[$classDiary_key]->diary;
            } else {
                return '';
            }
        }
    }

    public function saveStudentDiary($stageFk, $classroomFk, $date, $disciplineFk, $studentFk, $studentObservation)
    {
        // Fundamental menor
        $classroom = Classroom::model()->findByPk($classroomFk);
        $isMinorSchooling = $classroom->edcensoStageVsModalityFk->unified_frequency == 1 ? true : Yii::app()->utils->isStageMinorEducation($stageFk);
        if ($isMinorSchooling == '1') {
            $schedule = Schedule::model()->find('classroom_fk = :classroom_fk and day = :day and month = :month and unavailable = 0 group by day order by schedule, schedule', ['classroom_fk' => $classroomFk,
                'day' => DateTime::createFromFormat('d/m/Y', $date)->format('d'),
                'month' => DateTime::createFromFormat('d/m/Y', $date)->format('m')]);
        } else {
            $schedule = Schedule::model()->find('classroom_fk = :classroom_fk and day = :day and month = :month and discipline_fk = :discipline_fk and unavailable = 0 order by schedule, schedule', ['classroom_fk' => $classroomFk,
                'day' => DateTime::createFromFormat('d/m/Y', $date)->format('d'),
                'month' => DateTime::createFromFormat('d/m/Y', $date)->format('m'), 'discipline_fk' => $disciplineFk]);
        }

        if ($studentObservation != '') {
            $classDiary = ClassDiaries::model()->find('schedule_fk = :schedule_fk and student_fk = :student_fk', [':schedule_fk' => $schedule->id, ':student_fk' => $studentFk]);
            if ($classDiary == null) {
                $classDiary = new ClassDiaries();
                $classDiary->schedule_fk = $schedule->id;
                $classDiary->student_fk = $studentFk;
            }
            $classDiary->diary = $studentObservation === '' ? null : $studentObservation;
            if ($classDiary->save()) {
                // Atualização bem-sucedida
            } else {
                // Erro ao atualizar
                $errors = $classDiary->getErrors();
                var_dump($errors);
                exit();
            }
        } else {
            ClassDiaries::model()->deleteAll('schedule_fk = :schedule_fk and student_fk = :student_fk', [':schedule_fk' => $schedule->id, ':student_fk' => $studentFk]);
        }
    }
}
