<?php

Yii::import('application.modules.schoolreport.repositories.FormsRepository', true);

class EntryController extends Controller
{
    public function filters()
    {
        return [
            'accessControl',
        ];
    }

    public function accessRules()
    {
        return [
            [
                'allow',
                'actions' => [
                    'grades',
                    'getGrades',
                    'saveGrades',
                    'getDisciplines',
                    'calculateFinalMedia',
                    'reportCard',
                    'saveGradesReportCard',
                    'gradesRelease',
                ],
                'users' => ['@'],
            ],
            [
                'deny',
                'users' => ['*'],
            ],
        ];
    }

    public function actionGrades()
    {
        $year = Yii::app()->user->year;
        $school = Yii::app()->user->school;

        if (Yii::app()->getAuthManager()->checkAccess('instructor', Yii::app()->user->loginInfos->id)) {
            $criteria = new CDbCriteria();
            $criteria->alias = 'c';
            $criteria->join = ''
                . ' join instructor_teaching_data on instructor_teaching_data.classroom_id_fk = c.id '
                . ' join instructor_identification on instructor_teaching_data.instructor_fk = instructor_identification.id ';
            $criteria->condition = 'c.school_year = :school_year and c.school_inep_fk = :school_inep_fk and instructor_identification.users_fk = :users_fk';
            $criteria->order = 'name';
            $criteria->params = [':school_year' => $year, ':school_inep_fk' => $school, ':users_fk' => Yii::app()->user->loginInfos->id];

            $classroom = Classroom::model()->findAll($criteria);
            $classroom = CHtml::listData($classroom, 'id', 'name');
        } else {
            $classroom = Classroom::model()->findAll('school_year = :school_year and school_inep_fk = :school_inep_fk order by name', ['school_year' => $year, 'school_inep_fk' => $school]);
            $classroom = CHtml::listData($classroom, 'id', 'name');
        }

        $this->render('grades', ['classrooms' => $classroom]);
    }

    public function actionReportCard()
    {
        $year = Yii::app()->user->year;
        $school = Yii::app()->user->school;

        if (Yii::app()->getAuthManager()->checkAccess('instructor', Yii::app()->user->loginInfos->id)) {
            $criteria = new CDbCriteria();
            $criteria->alias = 'c';
            $criteria->join = ''
                . ' join instructor_teaching_data on instructor_teaching_data.classroom_id_fk = c.id '
                . ' join instructor_identification on instructor_teaching_data.instructor_fk = instructor_identification.id ';
            $criteria->condition = 'c.school_year = :school_year and c.school_inep_fk = :school_inep_fk and instructor_identification.users_fk = :users_fk';
            $criteria->order = 'name';
            $criteria->params = [':school_year' => $year, ':school_inep_fk' => $school, ':users_fk' => Yii::app()->user->loginInfos->id];

            $classroom = Classroom::model()->findAll($criteria);
            $classroom = CHtml::listData($classroom, 'id', 'name');
        } else {
            $classroom = Classroom::model()->findAll('school_year = :school_year and school_inep_fk = :school_inep_fk order by name', ['school_year' => $year, 'school_inep_fk' => $school]);
            $classroom = CHtml::listData($classroom, 'id', 'name');
        }

        $this->render('reportCard', ['classrooms' => $classroom]);
    }

    public function actionGradesRelease()
    {
        $year = Yii::app()->user->year;
        $school = Yii::app()->user->school;

        if (Yii::app()->getAuthManager()->checkAccess('instructor', Yii::app()->user->loginInfos->id)) {
            $criteria = new CDbCriteria();
            $criteria->alias = 'c';
            $criteria->join = ''
                . ' join instructor_teaching_data on instructor_teaching_data.classroom_id_fk = c.id '
                . ' join instructor_identification on instructor_teaching_data.instructor_fk = instructor_identification.id ';
            $criteria->condition = 'c.school_year = :school_year and c.school_inep_fk = :school_inep_fk and instructor_identification.users_fk = :users_fk';
            $criteria->order = 'name';
            $criteria->params = [':school_year' => $year, ':school_inep_fk' => $school, ':users_fk' => Yii::app()->user->loginInfos->id];

            $classroom = Classroom::model()->findAll($criteria);
            $classroom = CHtml::listData($classroom, 'id', 'name');
        } else {
            $classroom = Classroom::model()->findAll('school_year = :school_year and school_inep_fk = :school_inep_fk order by name', ['school_year' => $year, 'school_inep_fk' => $school]);
            $classroom = CHtml::listData($classroom, 'id', 'name');
        }

        $this->render('gradesRelease', ['classrooms' => $classroom]);
    }

    public function actionGetDisciplines()
    {
        $classroom = Classroom::model()->findByPk($_POST['classroom']);
        $disciplinesLabels = ClassroomHelper::classroomDisciplineLabelArray();
        if (Yii::app()->getAuthManager()->checkAccess('instructor', Yii::app()->user->loginInfos->id)) {
            $disciplines = Yii::app()->db->createCommand(
                'select ed.id from teaching_matrixes tm
                join instructor_teaching_data itd on itd.id = tm.teaching_data_fk
                join instructor_identification ii on ii.id = itd.instructor_fk
                join curricular_matrix cm on cm.id = tm.curricular_matrix_fk
                join edcenso_discipline ed on ed.id = cm.discipline_fk
                where ii.users_fk = :userid and itd.classroom_id_fk = :crid order by ed.name'
            )
                ->bindParam(':userid', Yii::app()->user->loginInfos->id)->bindParam(':crid', $classroom->id)->queryAll();
            foreach ($disciplines as $discipline) {
                echo htmlspecialchars(CHtml::tag('option', ['value' => $discipline['id']], CHtml::encode($disciplinesLabels[$discipline['id']]), true));
            }
        } else {
            echo CHtml::tag('option', ['value' => ''], CHtml::encode('Selecione...'), true);
            $classr = Yii::app()->db->createCommand(
                'select curricular_matrix.discipline_fk
                from curricular_matrix
                    join edcenso_discipline ed on ed.id = curricular_matrix.discipline_fk
                where stage_fk = :stage_fk and school_year = :year order by ed.name'
            )
                ->bindParam(':stage_fk', $classroom->edcenso_stage_vs_modality_fk)
                ->bindParam(':year', Yii::app()->user->year)->queryAll();
            foreach ($classr as $discipline) {
                if (isset($discipline['discipline_fk'])) {
                    echo htmlspecialchars(CHtml::tag('option', ['value' => $discipline['discipline_fk']], CHtml::encode($disciplinesLabels[$discipline['discipline_fk']]), true));
                }
            }
        }
    }

    public function actionSaveGradesReportCard()
    {
        $discipline = $_POST['discipline'];
        $classroomId = $_POST['classroom'];
        $students = $_POST['students'];

        $classroom = Classroom::model()->findByPk($classroomId);

        $gradeRules = GradeRules::model()->findByAttributes([
            'edcenso_stage_vs_modality_fk' => $classroom->edcenso_stage_vs_modality_fk
        ]);

        foreach ($students as $std) {
            $start = microtime(true);
            $gradeResult = (new GetStudentGradesResultUsecase($std['enrollmentId'], $discipline))->exec();
            $gradeResult->enrollment_fk = $std['enrollmentId'];
            $gradeResult->discipline_fk = $discipline;
            $gradeResult->rec_final = $std['recFinal'];

            $hasAllValues = true;
            foreach ($std['grades'] as $key => $value) {
                $index = $key + 1;
                $hasAllValues = $hasAllValues && (isset($gradeResult['grade_' . $index]) && $gradeResult['grade_' . $index] != '');
                $gradeResult->{ 'grade_' . $index} = $std['grades'][$key]['value'];
                $gradeResult->{ 'grade_faults_' . $index} = $std['grades'][$key]['faults'];
                $gradeResult->{ 'given_classes_' . $index} = $std['grades'][$key]['givenClasses'];
            }

            if (!$gradeResult->validate()) {
                throw new CHttpException(
                    '400',
                    'Não foi possível validar as notas adicionadas: ' . TagUtils::stringfyValidationErrors($gradeResult)
                );
            }

            $gradeResult->save();

            if ($hasAllValues) {
                $usecaseFinalMedia = new CalculateFinalMediaUsecase(
                    $gradeResult,
                    $gradeRules,
                    count($std['grades'])
                );
                $usecaseFinalMedia->exec();

                if ($gradeResult->enrollmentFk->isActive()) {
                    $usecase = new ChageStudentStatusByGradeUsecase(
                        $gradeResult,
                        $gradeRules,
                        count($std['grades'])
                    );
                    $usecase->exec();
                }
            }

            $timeElapsedSecs = microtime(true) - $start;
            Yii::log($std['enrollmentId'] . ' - ' . $timeElapsedSecs / 60, CLogger::LEVEL_INFO);
        }

        echo CJSON::encode(['valid' => true]);
    }

    public function actionSaveGrades()
    {
        $students = Yii::app()->request->getPost('students', []);
        $disciplineId = Yii::app()->request->getPost('discipline');
        $isConcept = Yii::app()->request->getPost('isConcept') === '1';

        $transaction = Yii::app()->db->beginTransaction();
        try {
            foreach ($students as $student) {
                foreach ($student['grades'] as $grade) {
                    $gradeModel = Grade::model()->findByAttributes([
                        'enrollment_fk' => $student['enrollmentId'],
                        'discipline_fk' => $disciplineId,
                        'grade_unity_modality_fk' => $grade['modalityId'],
                    ]);

                    if ($gradeModel === null) {
                        $gradeModel = new Grade();
                        $gradeModel->enrollment_fk = $student['enrollmentId'];
                        $gradeModel->discipline_fk = $disciplineId;
                        $gradeModel->grade_unity_modality_fk = $grade['modalityId'];
                    }

                    if ($isConcept) {
                        $gradeModel->grade = null;
                        $gradeModel->grade_concept_fk = $grade['concept'] !== '' ? $grade['concept'] : null;
                    } else {
                        $gradeModel->grade = $grade['value'] !== '' ? str_replace(',', '.', $grade['value']) : null;
                        $gradeModel->grade_concept_fk = null;
                    }

                    if (!$gradeModel->save()) {
                        throw new CHttpException(
                            400,
                            'Não foi possível salvar as notas: ' . TagUtils::stringfyValidationErrors($gradeModel)
                        );
                    }
                }
            }

            $transaction->commit();
            echo CJSON::encode(['valid' => true]);
        } catch (Exception $exception) {
            $transaction->rollback();
            throw $exception;
        }
    }

    public function actionGetGrades()
    {
        $criteria = new CDbCriteria();
        $criteria->alias = 'se';
        $criteria->join = 'join student_identification si on si.id = se.student_fk';
        $criteria->condition = 'classroom_fk = :classroom_fk';
        $criteria->params = [':classroom_fk' => $_POST['classroom']];
        $criteria->order = 'si.name';
        $studentEnrollments = StudentEnrollment::model()->findAll($criteria);

        if ($studentEnrollments != null) {
            $criteria = new CDbCriteria();
            $criteria->alias = 'gum';
            $criteria->join = 'join grade_unity gu on gu.id = gum.grade_unity_fk';
            $criteria->condition = 'edcenso_stage_vs_modality_fk = :stage';
            $criteria->params = [':stage' => $studentEnrollments[0]->classroomFk->edcenso_stage_vs_modality_fk];
            $gradeModalities = GradeUnityModality::model()->findAll($criteria);

            if ($gradeModalities != null) {
                $conceptOptions = GradeConcept::model()->findAll();
                foreach ($conceptOptions as $conceptOption) {
                    $result['conceptOptions'][$conceptOption->id] = $conceptOption->name;
                }
                $result['isUnityConcept'] = $gradeModalities[0]->gradeUnityFk->type == 'UC';
                $unityName = $gradeModalities[0]->gradeUnityFk->name;
                $unityColspan = 0;
                $result['unityColumns'] = [];
                $result['modalityColumns'] = [];
                foreach ($gradeModalities as $index => $gradeModality) {
                    array_push($result['modalityColumns'], $gradeModality->name);
                    if ($unityName == $gradeModality->gradeUnityFk->name) {
                        $unityColspan++;
                    } else {
                        array_push($result['unityColumns'], ['name' => $unityName, 'colspan' => $unityColspan]);
                        $unityName = $gradeModality->gradeUnityFk->name;
                        $unityColspan = 1;
                    }
                    if ($index == count($gradeModalities) - 1) {
                        array_push($result['unityColumns'], ['name' => $unityName, 'colspan' => $unityColspan]);
                    }
                }

                $result['students'] = [];
                foreach ($studentEnrollments as $studentEnrollment) {
                    $arr['enrollmentId'] = $studentEnrollment->id;
                    $arr['studentName'] = $studentEnrollment->studentFk->name;
                    $arr['grades'] = [];
                    foreach ($gradeModalities as $gradeModality) {
                        $gradeValue = '';
                        $gradeConcept = '';
                        $modalityId = $gradeModality->id;
                        foreach ($gradeModality->grades as $grade) {
                            if ($grade->enrollment_fk == $studentEnrollment->id && $grade->discipline_fk == $_POST['discipline']) {
                                $gradeValue = $grade->grade;
                                $gradeConcept = $grade->grade_concept_fk;
                                break;
                            }
                        }
                        array_push($arr['grades'], ['value' => $gradeValue, 'concept' => $gradeConcept, 'modalityId' => $modalityId]);
                    }
                    $gradeResult = GradeResults::model()->find('enrollment_fk = :enrollment_fk and discipline_fk = :discipline_fk', ['enrollment_fk' => $studentEnrollment->id, 'discipline_fk' => $_POST['discipline']]);
                    if (!$result['isUnityConcept']) {
                        $arr['finalMedia'] = $gradeResult != null ? $gradeResult->final_media : '';
                    }
                    $arr['situation'] = $gradeResult != null ? $this->checkGradesSituation($gradeResult->situation) : '';
                    array_push($result['students'], $arr);
                }

                $result['valid'] = true;
            } else {
                $result['valid'] = false;
                $result['message'] = 'Ainda não foi construída uma estrutura de unidades e avaliações para esta turma.';
            }
        } else {
            $result['valid'] = false;
            $result['message'] = 'Não há estudantes matriculados na turma.';
        }
        echo json_encode($result);
    }

    public function actionCalculateFinalMedia()
    {
        $disciplineId = $_POST['disciplineId'];
        $classroomId = $_POST['classroomId'];

        $classroom = Classroom::model()->with('activeStudentEnrollments')->findByPk($classroomId);

        $numUnities = GradeUnity::model()->count(
            'edcenso_stage_vs_modality_fk = :stageId and (type = :type or type = :type2 or type = :type3)',
            [
                ':stageId' => $classroom->edcenso_stage_vs_modality_fk,
                ':type' => GradeUnity::TYPE_UNITY,
                ':type2' => GradeUnity::TYPE_UNITY_WITH_RECOVERY,
                ':type3' => GradeUnity::TYPE_UNITY_BY_CONCEPT,
            ]
        );

        foreach ($classroom->activeStudentEnrollments as $enrollment) {
            $faults = $enrollment->countFaultsDiscipline($disciplineId);
            $contentsPerDiscipline = new FormsRepository();
            $contentsPerDiscipline = $contentsPerDiscipline->contentsPerDisciplineCalculate($enrollment->classroomFk, $disciplineId, $enrollment->id);

            $frequency = round((($contentsPerDiscipline - $faults) / ($contentsPerDiscipline ?: 1)) * 100);

            if ($enrollment->isActive()) {
                $usecase = new ChageStudentStatusByGradeUsecase(
                    $enrollment->id,
                    $disciplineId,
                    (int)$numUnities,
                    $frequency
                );
                $usecase->exec();
            }
        }

        echo CJSON::encode(['valid' => true]);
    }

    private function checkGradesSituation($situation)
    {
        return $situation != null ? $situation : '';
    }
}
