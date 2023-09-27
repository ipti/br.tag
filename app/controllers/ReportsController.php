<?php

require_once(__DIR__.'/../repository/ReportsRepository.php');

class ReportsController extends Controller
{
    public $layout = 'reportsclean';
    public $year = 0;

    public function accessRules()
    {
        return array(
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('index', 'BFReport', 'numberStudentsPerClassroomReport',
                    'InstructorsPerClassroomReport', 'StudentsFileReport',
                    'getStudentsFileInformation', 'ResultBoardReport',
                    'StatisticalDataReport', 'StudentsDeclarationReport',
                    'EnrollmentPerClassroomReport', 'AtaSchoolPerformance',
                    'EnrollmentDeclarationReport', 'TransferForm',
                    'StudentsWithDisabilitiesPerClassroom', 'StudentsWithDisabilitiesPerSchool',
                    'EnrollmentNotification', 'TransferRequirement',
                    'EnrollmentComparativeAnalysisReport', 'SchoolProfessionalNumberByClassroomReport',
                    'ComplementarActivityAssistantByClassroomReport', 'EducationalAssistantPerClassroomReport',
                    'DisciplineAndInstructorRelationReport', 'ClassroomWithoutInstructorRelationReport',
                    'StudentInstructorNumbersRelationReport', 'StudentPendingDocument',
                    'BFRStudentReport', 'ElectronicDiary', 'OutOfTownStudentsReport', 'StudentSpecialFood',
                    'ClassCouncilReport', 'QuarterlyReport', 'GetStudentClassrooms', 'QuarterlyFollowUpReport',
                    'EvaluationFollowUpStudentsReport', 'CnsPerClassroomReport', 'CnsSchools', 'CnsPerSchool',
                    'TeacherTrainingReport','ClassroomTransferReport', 'SchoolTransferReport', 'AllSchoolsTransferReport',
                    'TeachersByStage', 'TeachersBySchool', 'StatisticalData', 'NumberOfStudentsEnrolledPerPeriodPerClassroom',
                    'NumberOfStudentsEnrolledPerPeriodPerSchool', 'NumberOfStudentsEnrolledPerPeriodAllSchools',
                    'AllSchoolsReportOfStudentsBenefitingFromTheBF','AllClassroomsReportOfStudentsBenefitingFromTheBF',
                    'ReportOfStudentsBenefitingFromTheBFPerClassroom', 'TeachersByStage', 'TeachersBySchool', 'StatisticalData',
                    'NumberOfClassesPerSchool', 'NumberOfClassesPerSchool', 'StudentCpfRgNisPerClassroom',),
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function beforeAction($action)
    {
        if (Yii::app()->user->isGuest) {
            $this->redirect(yii::app()->createUrl('site/login'));
        }

        $this->year = Yii::app()->user->year;

        return true;
    }

    public function actionTotalNumberOfStudentsEnrolled()
    {
        $repository = new ReportsRepository;
        $query = $repository->getTotalNumberOfStudentsEnrolled();
        $this->render('TotalNumberOfStudentsEnrolled', $query);
    }

    public function actionStudentCpfRgNisAllSchools()
    {
        $repository = new ReportsRepository;
        $query = $repository->getStudentCpfRgNisAllSchools();
        $this->render('StudentCpfRgNis', $query);
    }

    public function actionStudentCpfRgNisAllClassrooms()
    {
        $repository = new ReportsRepository;
        $query = $repository->getStudentCpfRgNisAllClassrooms();
        $this->render('StudentCpfRgNis', $query);
    }

    public function actionStudentCpfRgNisPerClassroom()
    {
        $repository = new ReportsRepository;
        $query = $repository->getStudentCpfRgNisPerClassroom(Yii::app()->request);
        $this->render('StudentCpfRgNis', $query);
    }

    public function actionNumberOfStudentsEnrolledPerPeriodAllSchools()
    {
        $repository = new ReportsRepository;
        $query = $repository->getNumberOfStudentsEnrolledPerPeriodAllSchools(Yii::app()->request);
        $this->render('NumberOfStudentsEnrolledPerPeriod', $query);
    }

    public function actionNumberOfStudentsEnrolledPerPeriodPerSchool()
    {
        $repository = new ReportsRepository;
        $query = $repository->getNumberOfStudentsEnrolledPerPeriodPerSchool(Yii::app()->request);
        $this->render('NumberOfStudentsEnrolledPerPeriod', $query);
    }

    public function actionNumberOfStudentsEnrolledPerPeriodPerClassroom()
    {
        $repository = new ReportsRepository;
        $query = $repository->getNumberOfStudentsEnrolledPerPeriodPerClassroom(Yii::app()->request);
        $this->render('NumberOfStudentsEnrolledPerPeriod', $query);

    }

    public function actionAllClassroomsReportOfStudentsBenefitingFromTheBF()
    {
        $repository = new ReportsRepository;
        $query = $repository->getAllClassroomsReportOfStudentsBenefitingFromTheBF();
        $this->render('ReportOfStudentsBenefitingFromTheBF', $query);
    }

    public function actionAllSchoolsReportOfStudentsBenefitingFromTheBF()
    {
        $repository = new ReportsRepository;
        $query = $repository->getAllSchoolsReportOfStudentsBenefitingFromTheBF();
        $this->render('ReportOfStudentsBenefitingFromTheBF', $query);
    }

    public function actionReportOfStudentsBenefitingFromTheBFPerClassroom()
    {
        $repository = new ReportsRepository;
        $query = $repository->getReportOfStudentsBenefitingFromTheBFPerClassroom(Yii::app()->request);
        $this->render('ReportOfStudentsBenefitingFromTheBF', $query);
    }

    public function actionNumberOfClassesPerSchool()
    {
        $repository = new ReportsRepository;
        $query = $repository->getNumberOfClassesPerSchool();
        $this->render('NumberOfClassesPerSchool', $query);
    }

    public function actionTeacherTrainingReport()
    {
        $repository = new ReportsRepository;
        $query = $repository->getTeacherTrainingReport(Yii::app()->request);
        $this->render('buzios/TeacherTraining', $query);
    }

    public function actionStatisticalData()
    {
        $repository = new ReportsRepository;
        $query = $repository->getStatisticalData();
        $this->render('StatisticalData', $query);
    }

    public function actionClassroomTransferReport()
    {
        $repository = new ReportsRepository;
        $query = $repository->getClassroomTransferReport(Yii::app()->request);
        $this->render('TransferReport', $query);
    }

    public function actionSchoolTransferReport()
    {
        $repository = new ReportsRepository;
        $query = $repository->getSchoolTransferReport();
        $this->render('TransferReport', $query);
    }

    public function actionAllSchoolsTransferReport()
    {
        $repository = new ReportsRepository;
        $query = $repository->getAllSchoolsTransferReport();
        $this->render('TransferReport', $query);
    }
    public function actionTeachersByStage()
    {
        $repository = new ReportsRepository;
        $query = $repository->getTeachersByStage();
        $this->render('TeachersByStage', $query);
    }

    public function actionTeachersBySchool()
    {
        $repository = new ReportsRepository;
        $query = $repository->getTeachersBySchool();
        $this->render('TeachersBySchool', $query);
    }

    public function actionCnsPerClassroomReport()
    {
        $repository = new ReportsRepository;
        $query = $repository->getCnsPerClassroomReport(Yii::app()->request);
        $this->render('CnsReport', $query);
    }

    public function actionCnsSchools()
    {
        $repository = new ReportsRepository;
        $query = $repository->getCnsSchools();
        $this->render('CnsReport', $query);
    }

    public function actionCnsPerSchool()
    {
        $repository = new ReportsRepository;
        $query = $repository->getCnsPerSchool();
        $this->render('CnsReport', $query);
    }

    public function actionQuarterlyReport()
    {
        $repository = new ReportsRepository;
        $query = $repository->getQuarterlyReport(Yii::app()->request);
        if($query) {
            $this->render($query["view"], $query["response"]);
        }else {
            return $this->redirect(array('index'));
        }
    }

    public function actionQuarterlyFollowUpReport()
    {
        $repository = new ReportsRepository;
        $query = $repository->getQuarterlyFollowUpReport(Yii::app()->request);
        if($query["error"]) {
            Yii::app()->user->setFlash('error', Yii::t('default', $query["message"]));
            return $this->redirect(array('index'));
        }else {
            $this->render('buzios/quarterly/QuarterlyFollowUpReport', $query["response"]);
        }
    }

    public function actionEvaluationFollowUpStudentsReport()
    {
        $repository = new ReportsRepository;
        $query = $repository->getEvaluationFollowUpStudentsReport(Yii::app()->request);
        if($query["error"]) {
            Yii::app()->user->setFlash('error', Yii::t('default', $query["message"]));
            return $this->redirect(array('index'));
        }else {
            $this->render('buzios/quarterly/EvaluationFollowUpReport', $query["response"]);
        }
    }

    //@todo separar dos demais
    public function actionGetStudentClassrooms($id)
    {
        $classroom = Classroom::model()->findByPk($id);
        $enrollments = $classroom->studentEnrollments;
        foreach ($enrollments as $enrollment) {
            echo "<option value='" . $enrollment->studentFk->id . "'>" . htmlspecialchars($enrollment->studentFk->name, ENT_QUOTES, 'UTF-8') . "</option>";
        }
    }

    public function actionClassCouncilReport()
    {
        $repository = new ReportsRepository;
        $query = $repository->getClassCouncilReport(Yii::app()->request);
        if($query["error"]) {
            Yii::app()->user->setFlash('error', Yii::t('default', $query["message"]));
            return $this->redirect(array('index'));
        }else {
            $this->render($query["view"], $query["response"]);
        }
    }

    public function actionStudentsUsingSchoolTransportationRelationReport()
    {
        $repository = new ReportsRepository;
        $query = $repository->getStudentsUsingSchoolTransportationRelationReport();
        $this->render('StudentsUsingSchoolTransportationRelationReport', $query);
    }

    public function actionStudentsWithDisabilitiesPerClassroom()
    {
        $repository = new ReportsRepository;
        $query = $repository->getStudentsWithDisabilitiesPerClassroom(Yii::app()->request);
        $this->render('StudentsWithDisabilitiesPerClassroom', $query);
    }

    public function actionStudentsWithDisabilitiesPerSchool()
    {
        $repository = new ReportsRepository;
        $query = $repository->getStudentsWithDisabilitiesPerSchool();
        $this->render('StudentsWithDisabilitiesPerSchool', $query);
    }

    public function actionStudentsWithDisabilitiesRelationReport()
    {
        $repository = new ReportsRepository;
        $query = $repository->getStudentsWithDisabilitiesRelationReport();
        $this->render('StudentsWithDisabilitiesRelationReport', $query);
    }

    public function actionStudentsInAlphabeticalOrderRelationReport()
    {
        $repository = new ReportsRepository;
        $query = $repository->getStudentsInAlphabeticalOrderRelationReport();
        $this->render('StudentsInAlphabeticalOrderRelationReport', $query);
    }

    public function actionEnrollmentPerClassroomReport($id)
    {
        $repository = new ReportsRepository;
        $query = $repository->getEnrollmentPerClassroomReport($id);
        $this->render('EnrollmentPerClassroomReport', $query);
    }

    public function actionStudentPendingDocument()
    {
        $repository = new ReportsRepository;
        $query = $repository->getStudentPendingDocument();
        $this->render('StudentPendingDocument', $query);
    }

    public function actionStudentPerClassroom($id)
    {
        $repository = new ReportsRepository;
        $query = $repository->getStudentPerClassroom($id);
        $this->render('StudentPerClassroom', $query);
    }

    public function actionClocPerClassroom($id)
    {
        $repository = new ReportsRepository;
        $query = $repository->getClocPerClassroom($id);
        $this->render('ClocPerClassroom', $query);
    }

    public function actionStudentsByClassroomReport()
    {
        $repository = new ReportsRepository;
        $query = $repository->getStudentsByClassroomReport();
        $this->render('StudentsByClassroomReport', $query);
    }

    public function actionStudentsBetween5And14YearsOldReport()
    {
        $this->layout = "reportsclean";
        $repository = new ReportsRepository;
        $query = $repository->getStudentsBetween5And14YearsOldReport();
        $this->render('StudentsBetween5And14YearsOldReport', $query);
    }

    public function actionComplementarActivityAssistantByClassroomReport()
    {
        $repository = new ReportsRepository;
        $query = $repository->getComplementarActivityAssistantByClassroomReport();
        $this->render('ComplementarActivityAssistantByClassroomReport', $query);
    }

    public function actionDisciplineAndInstructorRelationReport()
    {
        $repository = new ReportsRepository;
        $query = $repository->getDisciplineAndInstructorRelationReport();
        $this->render('DisciplineAndInstructorRelationReport', $query);
    }

    public function actionIncompatibleStudentAgeByClassroomReport()
    {
        $repository = new ReportsRepository;
        $query = $repository->getIncompatibleStudentAgeByClassroomReport();
        $this->render('IncompatibleStudentAgeByClassroomReport', $query);
    }

    public function actionStudentsWithOtherSchoolEnrollmentReport()
    {
        $repository = new ReportsRepository;
        $query = $repository->getStudentsWithOtherSchoolEnrollmentReport();
        $this->render('StudentsWithOtherSchoolEnrollmentReport', $query);
    }

    public function actionEducationalAssistantPerClassroomReport()
    {
        $repository = new ReportsRepository;
        $query = $repository->getEducationalAssistantPerClassroomReport();
        $this->render('EducationalAssistantPerClassroomReport', $query);
    }

    public function actionClassroomWithoutInstructorRelationReport()
    {
        $repository = new ReportsRepository;
        $query = $repository->getClassroomWithoutInstructorRelationReport();
        $this->render('ClassroomWithoutInstructorRelationReport', $query);
    }

    public function actionStudentInstructorNumbersRelationReport()
    {
        $repository = new ReportsRepository;
        $query = $repository->getStudentInstructorNumbersRelationReport();
        $this->render('StudentInstructorNumbersRelationReport', $query);
    }

    public function actionSchoolProfessionalNumberByClassroomReport()
    {
        $repository = new ReportsRepository;
        $query = $repository->getSchoolProfessionalNumberByClassroomReport();
        $this->render('SchoolProfessionalNumberByClassroomReport', $query);
    }

    public function actionStudentByClassroomReport()
    {
        $repository = new ReportsRepository;
        $query = $repository->getStudentByClassroom(Yii::app()->request);
        $this->render('StudentByClassroomReport', $query);
    }

    public function actionEnrollmentComparativeAnalysisReport()
    {
        $repository = new ReportsRepository;
        $query = $repository->getEnrollmentComparativeAnalysis();
        $this->render('EnrollmentComparativeAnalysisReport', $query);
    }

    public function actionNumberStudentsPerClassroomReport()
    {
        $repository = new ReportsRepository;
        $query = $repository->getNumberStudentsPerClassroom();
        $this->render('NumberStudentsPerClassroomReport', $query);
    }

    public function actionClocReport()
    {
        $repository = new ReportsRepository;
        $query = $repository->getClocReport();
        $this->render('NumberStudentsPerClassroomReport', $query);
    }

    public function actionInstructorsPerClassroomReport()
    {
        $repository = new ReportsRepository;
        $query = $repository->getInstructorsPerClassroom();
        $this->render('InstructorsPerClassroomReport', $query);
    }

    public function actionBFReport()
    {
        $repository = new ReportsRepository;
        $query = $repository->getAttendanceForBF(Yii::app()->request);
        $this->render('BFReport', $query);
    }


    public function actionBFRStudentReport()
    {
        $repository = new ReportsRepository;
        $query = $repository->getStudentsParticipatingInBF();
        $this->render('BFRStudentReport', $query);
    }


    public function actionIndex()
    {
        $this->layout = "fullmenu";
        $repository = new ReportsRepository;
        $data = $repository->getIndexData();
        $this->render('index', $data);
    }

    public function actionElectronicDiary()
    {
        $this->layout = "fullmenu";
        $repository = new ReportsRepository;
        $data = $repository->getElectronicDiary();
        $this->render('ElectronicDiary', $data);
    }

    //@todo MARCAÇÃO PARA CONTINUAR DPS
    public function actionGetDisciplines()
    {
        $classroom = Classroom::model()->findByPk($_POST["classroom"]);
        $disciplinesLabels = ClassroomController::classroomDisciplineLabelArray();
        if (Yii::app()->getAuthManager()->checkAccess('instructor', Yii::app()->user->loginInfos->id)) {
            $disciplines = Yii::app()->db->createCommand(
                "select ed.id from teaching_matrixes tm
                join instructor_teaching_data itd on itd.id = tm.teaching_data_fk
                join instructor_identification ii on ii.id = itd.instructor_fk
                join curricular_matrix cm on cm.id = tm.curricular_matrix_fk
                join edcenso_discipline ed on ed.id = cm.discipline_fk
                where ii.users_fk = :userid and itd.classroom_id_fk = :crid order by ed.name"
            )
                ->bindParam(":userid", Yii::app()->user->loginInfos->id)->bindParam(":crid", $classroom->id)->queryAll();
            foreach ($disciplines as $discipline) {
                echo htmlspecialchars(CHtml::tag('option', array('value' => $discipline['id']), CHtml::encode($disciplinesLabels[$discipline['id']]), true));
            }
        } else {
            echo CHtml::tag('option', array('value' => ""), CHtml::encode('Selecione...'), true);
            $classr = Yii::app()->db->createCommand("select curricular_matrix.discipline_fk from curricular_matrix where stage_fk = :stage_fk and school_year = :year")->bindParam(":stage_fk", $classroom->edcenso_stage_vs_modality_fk)->bindParam(":year", Yii::app()->user->year)->queryAll();
            foreach ($classr as $i => $discipline) {
                if (isset($discipline['discipline_fk'])) {
                    echo htmlspecialchars(CHtml::tag('option', array('value' => $discipline['discipline_fk']), CHtml::encode($disciplinesLabels[$discipline['discipline_fk']]), true));
                }
            }
        }
    }

    public function actionGetEnrollments()
    {
        $criteria = new CDbCriteria();
        $criteria->alias = "se";
        $criteria->join = "join student_identification si on si.id = se.student_fk";
        $criteria->condition = "classroom_fk = :classroom_fk";
        $criteria->params = array(':classroom_fk' => $_POST["classroom"]);
        $criteria->order = "si.name";
        $studentEnrollments = StudentEnrollment::model()->findAll($criteria);
        echo CHtml::tag('option', array('value' => ""), CHtml::encode('Selecione...'), true);
        foreach ($studentEnrollments as $studentEnrollment) {
            echo htmlspecialchars(CHtml::tag('option', array('value' => $studentEnrollment['id']), $studentEnrollment->studentFk->name, true));
        }
    }

    public function actionGenerateElectronicDiaryReport()
    {
        $result = [];
        if ($_POST["type"] === "frequency") {
            $initial_date = $_POST["initialDate"];
            $final_date = $_POST["finalDate"];
            $fundamental_maior = $_POST["fundamentalMaior"];
            $classroom = $_POST["classroom"];
            $result = $this->frequencyReport($initial_date, $final_date, $fundamental_maior, $classroom);
        } elseif ($_POST["type"] === "gradesByStudent") {
            $result = $this->gradesReport($_POST["classroom"], $_POST["student"]);
        }
        echo json_encode($result);
    }

    private function frequencyReport($initial_date, $final_date, $fundamental_maior, $classroom)
    {


        $arr = explode('/', $initial_date);
        $initialDate = $arr[2] . "-" . $arr[1] . "-" . $arr[0];
        $arr = explode('/', $final_date);
        $finalDate = $arr[2] . "-" . $arr[1] . "-" . $arr[0];
        $students = [];
        if ($fundamental_maior == "1") {
            $schedules = Schedule::model()
                ->findAll(
                    "classroom_fk = :classroom_fk and date_format(concat(" . Yii::app()->user->year . ", '-', month, '-', day), '%Y-%m-%d') between :initial_date and :final_date and discipline_fk = :discipline_fk and unavailable = 0 order by month, day, schedule",
                    ["classroom_fk" => $classroom, "initial_date" => $initialDate, "final_date" => $finalDate, "discipline_fk" => $_POST["discipline"]]
                );
            if ($schedules !== null) {
                foreach ($schedules[0]->classroomFk->studentEnrollments as $studentEnrollment) {
                    array_push($students, ["id" => $studentEnrollment->student_fk, "name" => $studentEnrollment->studentFk->name, "total" => count($schedules), "faults" => [], "frequency" => ""]);
                }
                foreach ($schedules as $schedule) {
                    foreach ($schedule->classFaults as $classFault) {
                        $key = array_search($classFault->student_fk, array_column($students, 'id'));
                        array_push($students[$key]["faults"], str_pad($schedule["day"], 2, "0", STR_PAD_LEFT) . "/" . str_pad($schedule["month"], 2, "0", STR_PAD_LEFT) . " (" . $schedule["schedule"] . "º Hor.)");
                    }
                }
                foreach ($students as &$student) {
                    $student["frequency"] = (floor((($student["total"] - count($student["faults"])) / $student["total"]) * 100 * 100) / 100) . "%";
                }
                unset($student);
            }
        } else {
            $schedules = Schedule::model()
                ->findAll(
                    "classroom_fk = :classroom_fk and date_format(concat(" . Yii::app()->user->year . ", '-', month, '-', day), '%Y-%m-%d') between :initial_date and :final_date and unavailable = 0 order by month, day",
                    ["classroom_fk" => $classroom, "initial_date" => $initialDate, "final_date" => $finalDate]
                );
            if ($schedules !== null) {
                foreach ($schedules[0]->classroomFk->studentEnrollments as $studentEnrollment) {
                    array_push($students, ["id" => $studentEnrollment->student_fk, "name" => $studentEnrollment->studentFk->name, "days" => 0, "faults" => [], "frequency" => ""]);
                }
                $days = [];
                foreach ($schedules as $schedule) {
                    if (!in_array($schedule["day"] . $schedule["month"], $days)) {
                        array_push($days, $schedule["day"] . $schedule["month"]);
                    }
                    foreach ($schedule->classFaults as $classFault) {
                        $key = array_search($classFault->student_fk, array_column($students, 'id'));
                        if (!in_array(str_pad($schedule["day"], 2, "0", STR_PAD_LEFT) . "/" . str_pad($schedule["month"], 2, "0", STR_PAD_LEFT), $students[$key]["faults"])) {
                            array_push($students[$key]["faults"], str_pad($schedule["day"], 2, "0", STR_PAD_LEFT) . "/" . str_pad($schedule["month"], 2, "0", STR_PAD_LEFT));
                        }
                    }
                }
                foreach ($students as &$student) {
                    $student["total"] = count($days);
                    $student["frequency"] = (floor((($student["total"] - count($student["faults"])) / $student["total"]) * 100 * 100) / 100) . "%";
                }
            }
        }
        $col = array_column($students, "name");
        array_multisort($col, SORT_ASC, $students);
        $result["students"] = $students;

        return $result;
    }

    private function gradesReport($classroom_id, $student_id)
    {
        $classroom = Classroom::model()
            ->with('edcensoStageVsModalityFk.gradeUnities')
            ->find("t.id = :classroom", [":classroom" => $classroom_id]);

        $gradeUnitiesByClassroom = $classroom->edcensoStageVsModalityFk->gradeUnities;
        if ($gradeUnitiesByClassroom !== null) {
            $result["isUnityConcept"] = $gradeUnitiesByClassroom[0]->type == "UC";
            $result["unityNames"] = [];
            $result["subunityNames"] = [];

            foreach ($gradeUnitiesByClassroom as $gradeUnity) {
                array_push($result["unityNames"], ["name" => $gradeUnity["name"], "colspan" => $gradeUnity->type == "UR" ? 2 : 1]);
                $commonModalitiesName = "";
                $recoverModalityName = "";
                $firstCommonModality = false;
                foreach ($gradeUnity->gradeUnityModalities as $index => $gradeUnityModality) {
                    if ($gradeUnityModality->type == "C") {
                        if (!$firstCommonModality) {
                            $commonModalitiesName .= $gradeUnityModality->name;
                            $firstCommonModality = true;
                        } else {
                            $commonModalitiesName .= " + " . $gradeUnityModality->name;
                        }
                    } else {
                        $recoverModalityName = $gradeUnityModality->name;
                    }
                }
                array_push($result["subunityNames"], $commonModalitiesName);
                if ($recoverModalityName !== "") {
                    array_push($result["subunityNames"], $recoverModalityName);
                }
            }

            //Montar linhas das disciplinas e notas
            $result["rows"] = [];
            $disciplines = Yii::app()->db->createCommand("
                select ed.id, ed.name from curricular_matrix cm
                join edcenso_discipline ed on ed.id = cm.discipline_fk
                join edcenso_stage_vs_modality esvm on esvm.id = cm.stage_fk
                join classroom c on c.edcenso_stage_vs_modality_fk = esvm.id
                where c.id = :classroom
                order by ed.name
            ")->bindParam(":classroom", $classroom_id)->queryAll();

            foreach ($disciplines as $discipline) {
                $arr["disciplineName"] = $discipline["name"];

                $arr["grades"] = [];
                foreach ($gradeUnitiesByClassroom as $gradeUnity) {
                    array_push($arr["grades"], $gradeUnity->type == "UR"
                        ? ["unityId" => $gradeUnity->id, "unityGrade" => "", "unityRecoverGrade" => "", "gradeUnityType" => $gradeUnity->type]
                        : ["unityId" => $gradeUnity->id, "unityGrade" => "", "gradeUnityType" => $gradeUnity->type]);
                }

                $gradeResult = GradeResults::model()->find("enrollment_fk = :enrollment_fk and discipline_fk = :discipline_fk", ["enrollment_fk" => $student_id, "discipline_fk" => $discipline["id"]]);
                $recSemIndex = 0;
                $gradeIndex = 0;
                foreach ($arr["grades"] as &$grade) {
                    switch ($grade["gradeUnityType"]) {
                        case "U":
                            $grade["unityGrade"] = $gradeResult["grade_" . ($gradeIndex + 1)] != null ? $gradeResult["grade_" . ($gradeIndex + 1)] : "";
                            $gradeIndex++;
                            break;
                        case "UR":
                            $grade["unityGrade"] = $gradeResult["grade_" . ($gradeIndex + 1)] != null ? $gradeResult["grade_" . ($gradeIndex + 1)] : "";
                            $grade["unityRecoverGrade"] = $gradeResult["rec_bim_" . ($gradeIndex + 1)] != null ? $gradeResult["rec_bim_" . ($gradeIndex + 1)] : "";
                            $gradeIndex++;
                            break;
                        case "RS":
                            $grade["unityGrade"] = $gradeResult["rec_sem_" . ($recSemIndex + 1)] != null ? $gradeResult["rec_sem_" . ($recSemIndex + 1)] : "";
                            $recSemIndex++;
                            break;
                        case "RF":
                            $grade["unityGrade"] = $gradeResult["rec_final"] != null ? $gradeResult["rec_final"] : "";
                            break;
                        case "UC":
                            $grade["unityGrade"] = $gradeResult["grade_concept_" . ($gradeIndex + 1)] != null ? $gradeResult["grade_concept_" . ($gradeIndex + 1)] : "";
                            $gradeIndex++;
                            break;
                    }
                }

                $arr["finalMedia"] = $gradeResult != null ? $gradeResult->final_media : "";
                $arr["situation"] = $gradeResult != null ? ($gradeResult->situation != null ? $gradeResult->situation : "") : "";
                array_push($result["rows"], $arr);
            }
            $result["valid"] = true;
        } else {
            $result["valid"] = false;
        }

        return $result;
    }

    public function actionOutOfTownStudentsReport()
    {
        $sql = "SELECT DISTINCT su.name, su.inep_id, su.birthday, std.address,
                edcstd.name AS city_student, edcsch.name AS city_school,
                si.name AS school
                FROM student_documents_and_address std
                JOIN edcenso_city edcstd ON(std.edcenso_city_fk = edcstd.id)
                JOIN student_enrollment se ON(std.id = se.student_fk)
                JOIN classroom cl ON(se.classroom_fk = cl.id)
                JOIN school_identification si ON (si.inep_id = cl.school_inep_fk)
                JOIN edcenso_city edcsch ON(si.edcenso_city_fk = edcsch.id)
                JOIN student_identification su ON(su.id= std.id)
                WHERE si.`inep_id` =" . Yii::app()->user->school . " AND (se.status = 1 OR se.status IS NULL)
                AND (si.edcenso_city_fk != std.edcenso_city_fk)
                AND (cl.school_year =" . Yii::app()->user->year . ")
                ORDER BY NAME;";

        $result = Yii::app()->db->createCommand($sql)->queryAll();
        $this->render('OutOfTownStudentsReport', array(
            'report' => $result,
        ));
    }

    public function actionStudentSpecialFood()
    {
        $sql = "SELECT si.inep_id , si.name as nome_aluno, si.birthday, sr.* FROM student_identification si
        JOIN student_restrictions sr ON(sr.student_fk = si.id)
        WHERE sr.celiac != 0
        OR sr.celiac != 0
        OR sr.diabetes  != 0
        OR sr.hypertension  != 0
        OR sr.iron_deficiency_anemia != 0
        OR sr.sickle_cell_anemia != 0
        OR sr.lactose_intolerance != 0
        OR sr.malnutrition != 0
        OR sr.obesity != 0
        OR sr.`others` != ''";

        $result = Yii::app()->db->createCommand($sql)->queryAll();

        $this->render('StudentSpecialFood', array(
            'report' => $result,
        ));
    }

}
