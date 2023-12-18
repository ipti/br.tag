<?php

require_once 'app/vendor/autoload.php';
Yii::import('application.modules.sedsp.models.Classroom.*');
Yii::import('application.modules.sedsp.models.Enrollment.*');
Yii::import('application.modules.sedsp.models.Student.*');
Yii::import('application.modules.sedsp.datasources.sed.Classroom.*');
Yii::import('application.modules.sedsp.datasources.sed.ClassStudentsRelation.*');
Yii::import('application.modules.sedsp.datasources.sed.Enrollment.*');
Yii::import('application.modules.sedsp.datasources.sed.Student.*');
Yii::import('application.modules.sedsp.mappers.*');
Yii::import('application.modules.sedsp.usecases.*');
Yii::import('application.modules.sedsp.usecases.Enrollment.*');

//-----------------------------------------CLASSE VALIDADA ATÉ A SEQUENCIA 35!!------------------------
class ClassroomController extends Controller
{

    const CREATE = 'create';
    const UPDATE = 'update';
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = 'fullmenu';
    public $MODEL_CLASSROOM = 'Classroom';
    public $MODEL_TEACHING_DATA = 'InstructorTeachingData';
    public $MODEL_STUDENT_ENROLLMENT = 'StudentEnrollment';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array(
                'allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array(
                    'index', 'view', 'create', 'update', 'getassistancetype',
                    'updateassistancetypedependencies', 'updatecomplementaryactivity',
                    'batchupdatenrollment',
                    'getcomplementaryactivitytype', 'delete',
                    'updateTime', 'move', 'batchupdate', 'batchupdatetotal', 'changeenrollments', 'batchupdatetransport', 'updateDisciplines', 'syncToSedsp', 'syncUnsyncedStudents'
                ),
                'users' => array('@'),
            ),
            array(
                'allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin'),
                'users' => array('admin'),
            ),
            array(
                'deny', // deny all users
                'users' => array('*'),
            ),
        );
    }


    private function defineAssistanceType($classroom)
    {
        $isAee = $classroom['aee'];
        $isComplementaryAct = $classroom['complementary_activity'];
        $isSchooling = $classroom['schooling'];

        if (isset($isAee) && $isAee) {
            return 5;
        }
        if (isset($isComplementaryAct) && $isComplementaryAct) {
            return 4;
        }
        if (isset($isSchooling) && $isSchooling) {

            return 0;
        }
    }

    public function actionGetAssistanceType()
    {
        $classroom = new Classroom();
        $classroom->attributes = $_POST['Classroom'];
        $schoolStructure = SchoolStructure::model()->findByPk($classroom->school_inep_fk);
        $result = array('html' => '', 'val' => '');

        $result['html'] = CHtml::tag('option', array('value' => ''), CHtml::encode('Selecione o Tipo de Atendimento'), true);
        if ($schoolStructure != null) {

            $encode = array(
                0 => CHtml::encode('Não se Aplica'),
                1 => CHtml::encode('Classe Hospitalar'),
                2 => CHtml::encode('Unidade de Internação Socioeducativa'),
                3 => CHtml::encode('Unidade Prisional'),
                4 => CHtml::encode('Atividade Complementar'),
                5 => CHtml::encode('Atendimento Educacional Especializado (AEE)')
            );

            $selected = array(
                0 => $classroom->assistance_type == 0 ? "selected" : "deselected",
                1 => $classroom->assistance_type == 1 ? "selected" : "deselected",
                2 => $classroom->assistance_type == 2 ? "selected" : "deselected",
                3 => $classroom->assistance_type == 3 ? "selected" : "deselected",
                4 => $classroom->assistance_type == 4 ? "selected" : "deselected",
                5 => $classroom->assistance_type == 5 ? "selected" : "deselected"
            );

            for ($i = 0; $i <= 3; $i++) {
                $result['html'] .= CHtml::tag('option', array('value' => "$i", $selected[$i] => $selected[$i]), $encode[$i], true);
            }
            if ($schoolStructure->complementary_activities == 1 || $schoolStructure->complementary_activities == 2) {
                $result['html'] .= CHtml::tag('option', array('value' => "4", $selected[4] => $selected[4]), $encode[4], true);
            }
            if ($schoolStructure->aee == 1 || $schoolStructure->aee == 2) {
                $result['html'] .= CHtml::tag('option', array('value' => "5", $selected[5] => $selected[5]), $encode[5], true);
            }
        }
        $result['val'] = $classroom->assistance_type;
        echo json_encode($result);
    }

    public function actionUpdateComplementaryActivity()
    {
        $classroom = new Classroom();
        $classroom->attributes = $_POST['Classroom'];
        $id1 = $classroom->complementary_activity_type_1;
        $id2 = $classroom->complementary_activity_type_2;
        $id3 = $classroom->complementary_activity_type_3;
        $id4 = $classroom->complementary_activity_type_4;
        $id5 = $classroom->complementary_activity_type_5;
        $id6 = $classroom->complementary_activity_type_6;

        $where = '';
        $where .= $id1 != 'null' ? 'id!="' . $id1 . '" ' : '';
        $where .= $id2 != 'null' ? '&& id!="' . $id2 . '" ' : '';
        $where .= $id3 != 'null' ? '&& id!="' . $id3 . '" ' : '';
        $where .= $id4 != 'null' ? '&& id!="' . $id4 . '" ' : '';
        $where .= $id5 != 'null' ? '&& id!="' . $id5 . '" ' : '';
        $where .= $id6 != 'null' ? '&& id!="' . $id6 . '" ' : '';


        $data = EdcensoComplementaryActivityType::model()->findAll($where);
        $data = CHtml::listData($data, 'id', 'name');

        echo CHtml::tag('option', array('value' => ''), CHtml::encode('Selecione a atividade complementar'), true);
        foreach ($data as $value => $name) {
            echo CHtml::tag('option', array('value' => $value), CHtml::encode($name), true);
        }
    }

    public function actionUpdateAssistanceTypeDependencies()
    {
        /* 	Campo	18	Se Campo 17 = 1|5, desabilita;
          Se Campo 17 = 0|2|3, campo 36 hanilita 1 e 2 e campo 37 habilita [4..38]|41|56;
          Se Campo 17 = 4, campo 36&37 = null
          Campo	19~24	Se Campo 17 = 4; Pelo menos um, Não repetidos.
          Campo 	25~35	Se Campo 17 = 5; Pelo menos um diferente de 0.
         *
         * 17 tipo de atendimento
         * 18 mais edu
         * 19~24 tipo de atividade
         * 25~35 atividades
         */

        $classroom = new Classroom();
        $classroom->attributes = $_POST['Classroom'];

        $result = array('Stage' => '', 'MaisEdu' => '', 'Modality' => '', 'AeeActivity' => '');

        $at = $classroom->assistance_type;

        $result['MaisEdu'] = $at == 1 || $at == 5;
        $result['AeeActivity'] = $at != 5;
        $modality = $at != 4 || $at != 5;

        $where = '';
        $result['Modality'] = CHtml::tag('option', array('value' => ''), CHtml::encode('Selecione a Modalidade'), true);

        if ($result['MaisEdu']) {
            $where = '(id<4 || id>38) && id!=38 && id!=41 && id!=56';
        }

        if ($modality) {
            $result['Modality'] .= CHtml::tag('option', array('value' => '1', $classroom->modality == 1 ? "selected" : "deselected" => $classroom->modality == 1 ? "selected" : "deselected"), CHtml::encode('Ensino Regular'), true);
            $result['Modality'] .= CHtml::tag('option', array('value' => '2', $classroom->modality == 2 ? "selected" : "deselected" => $classroom->modality == 2 ? "selected" : "deselected"), CHtml::encode('Educação Especial - Modalidade Substitutiva'), true);
            $result['Modality'] .= CHtml::tag('option', array('value' => '3', $classroom->modality == 3 ? "selected" : "deselected" => $classroom->modality == 3 ? "selected" : "deselected"), CHtml::encode('Educação de Jovens e Adultos (EJA)'), true);
        }

        $result['StageEmpty'] = false;
        if ($at == 0 || $at == 1) {
            $data = EdcensoStageVsModality::model()->findAll($where);
        } elseif ($at == 2 || $at == 3) {
            $data = EdcensoStageVsModality::model()->findAll('id!=1 && id!=2 && id!=3 && id!=56 ' . $where);
        } else {
            $data = array();
            $result['StageEmpty'] = true;
        }

        $data = CHtml::listData($data, 'id', 'name');

        $result['Stage'] = CHtml::tag('option', array('value' => ''), 'Selecione a Etapa de Ensino', true);

        foreach ($data as $value => $name) {
            $result['Stage'] .= CHtml::tag('option', array('value' => $value, $classroom->edcenso_stage_vs_modality_fk == $value ? "selected" : "deselected" => $classroom->edcenso_stage_vs_modality_fk == $value ? "selected" : "deselected"), CHtml::encode($name), true);
        }

        echo json_encode($result);
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    public function setDisciplines($modelClassroom, $discipline)
    {

        $type = $modelClassroom->assistance_type;
        $stage = $modelClassroom->edcenso_stage_vs_modality_fk;
        $putNull = ($type == 4 || $type == 5) || ($stage == 1 || $stage == 2 || $stage == 3 || $stage == 65);


        $modelClassroom->discipline_chemistry = $putNull ? null : (isset($discipline[1]) ? $discipline[1] : 0);
        $modelClassroom->discipline_physics = $putNull ? null : (isset($discipline[2]) ? $discipline[2] : 0);
        $modelClassroom->discipline_mathematics = $putNull ? null : (isset($discipline[3]) ? $discipline[3] : 0);
        $modelClassroom->discipline_biology = $putNull ? null : (isset($discipline[4]) ? $discipline[4] : 0);
        $modelClassroom->discipline_science = $putNull ? null : (isset($discipline[5]) ? $discipline[5] : 0);
        $modelClassroom->discipline_language_portuguese_literature = $putNull ? null : (isset($discipline[6]) ? $discipline[6] : 0);
        $modelClassroom->discipline_foreign_language_english = $putNull ? null : (isset($discipline[7]) ? $discipline[7] : 0);
        $modelClassroom->discipline_foreign_language_spanish = $putNull ? null : (isset($discipline[8]) ? $discipline[8] : 0);
        $modelClassroom->discipline_foreign_language_other = $putNull ? null : (isset($discipline[9]) ? $discipline[9] : 0);
        $modelClassroom->discipline_arts = $putNull ? null : (isset($discipline[10]) ? $discipline[10] : 0);
        $modelClassroom->discipline_physical_education = $putNull ? null : (isset($discipline[11]) ? $discipline[11] : 0);
        $modelClassroom->discipline_history = $putNull ? null : (isset($discipline[12]) ? $discipline[12] : 0);
        $modelClassroom->discipline_geography = $putNull ? null : (isset($discipline[13]) ? $discipline[13] : 0);
        $modelClassroom->discipline_philosophy = $putNull ? null : (isset($discipline[14]) ? $discipline[14] : 0);
        $modelClassroom->discipline_informatics = $putNull ? null : (isset($discipline[16]) ? $discipline[16] : 0);
        $modelClassroom->discipline_professional_disciplines = $putNull ? null : (isset($discipline[17]) ? $discipline[17] : 0);
        $modelClassroom->discipline_special_education_and_inclusive_practices = $putNull ? null : (isset($discipline[20]) ? $discipline[20] : 0);
        $modelClassroom->discipline_sociocultural_diversity = $putNull ? null : (isset($discipline[21]) ? $discipline[21] : 0);
        $modelClassroom->discipline_libras = $putNull ? null : (isset($discipline[23]) ? $discipline[23] : 0);
        $modelClassroom->discipline_pedagogical = $putNull ? null : (isset($discipline[25]) ? $discipline[25] : 0);
        $modelClassroom->discipline_religious = $putNull ? null : (isset($discipline[26]) ? $discipline[26] : 0);
        $modelClassroom->discipline_native_language = $putNull ? null : (isset($discipline[27]) ? $discipline[27] : 0);
        $modelClassroom->discipline_social_study = $putNull ? null : (isset($discipline[28]) ? $discipline[28] : 0);
        $modelClassroom->discipline_sociology = $putNull ? null : (isset($discipline[29]) ? $discipline[29] : 0);
        $modelClassroom->discipline_foreign_language_franch = $putNull ? null : (isset($discipline[30]) ? $discipline[30] : 0);
        $modelClassroom->discipline_others = $putNull ? null : (isset($discipline[99]) ? $discipline[99] : 0);
    }

    //@done s1 - criar função para pegar os labels das disciplinas separando pelo id do educacenso

    public static function classroomDisciplineLabelArray()
    {
        $labels = array();
        $disciplines = EdcensoDiscipline::model()->findAll(['select' => 'id, name']);
        foreach ($disciplines as $value) {
            $labels[$value->id] = $value->name;
        }
        return $labels;
    }

    public static function classroomDisciplineLabelResumeArray()
    {
        $labels = array();
        $disciplines = EdcensoDiscipline::model()->findAll(['select' => 'id, name']);
        foreach ($disciplines as $value) {
            $labels[$value->id] = $value->name;
        }
        return $labels;
    }


    public static function classroomDiscipline2array2()
    {
        $disciplines['discipline_chemistry'] = 1;
        $disciplines['discipline_physics'] = 2;
        $disciplines['discipline_mathematics'] = 3;
        $disciplines['discipline_biology'] = 4;
        $disciplines['discipline_science'] = 5;
        $disciplines['discipline_language_portuguese_literature'] = 6;
        $disciplines['discipline_foreign_language_english'] = 7;
        $disciplines['discipline_foreign_language_spanish'] = 8;
        $disciplines['discipline_foreign_language_other'] = 9;
        $disciplines['discipline_arts'] = 10;
        $disciplines['discipline_physical_education'] = 11;
        $disciplines['discipline_history'] = 12;
        $disciplines['discipline_geography'] = 13;
        $disciplines['discipline_philosophy'] = 14;
        $disciplines['discipline_informatics'] = 16;
        $disciplines['discipline_professional_disciplines'] = 17;
        $disciplines['discipline_special_education_and_inclusive_practices'] = 20;
        $disciplines['discipline_sociocultural_diversity'] = 21;
        $disciplines['discipline_libras'] = 23;
        $disciplines['discipline_religious'] = 26;
        $disciplines['discipline_native_language'] = 27;
        $disciplines['discipline_pedagogical'] = 25;
        $disciplines['discipline_social_study'] = 28;
        $disciplines['discipline_sociology'] = 29;
        $disciplines['discipline_foreign_language_franch'] = 30;
        $disciplines['discipline_others'] = 99;
        return $disciplines;
    }

    //@done s1 - criar função para transformar as disciplinas do Classroom em Array

    public static function classroomDiscipline2array($classroom)
    {

        $disciplines = array();
        $classroomModel = Classroom::model()
            ->with("edcensoStageVsModalityFk.curricularMatrixes.disciplineFk")
            ->find("t.id = :classroom", [":classroom" => $classroom->id]);

        foreach ($classroomModel->edcensoStageVsModalityFk->curricularMatrixes as $key => $matrix) {
            $disciplines[$matrix->disciplineFk->id] = $matrix->disciplineFk->name;
        }

        return $disciplines;
    }

    //@done s1 - criar função para transformas as Disciplinas do TeachingData em Array

    public static function teachingDataDiscipline2array($instructor)
    {
        $disciplines = array();

        if (isset($instructor->discipline_1_fk)) {
            array_push($disciplines, $instructor->discipline1Fk);
        }
        if (isset($instructor->discipline_2_fk)) {
            array_push($disciplines, $instructor->discipline2Fk);
        }
        if (isset($instructor->discipline_3_fk)) {
            array_push($disciplines, $instructor->discipline3Fk);
        }
        if (isset($instructor->discipline_4_fk)) {
            array_push($disciplines, $instructor->discipline4Fk);
        }
        if (isset($instructor->discipline_5_fk)) {
            array_push($disciplines, $instructor->discipline5Fk);
        }
        if (isset($instructor->discipline_6_fk)) {
            array_push($disciplines, $instructor->discipline6Fk);
        }
        if (isset($instructor->discipline_7_fk)) {
            array_push($disciplines, $instructor->discipline7Fk);
        }
        if (isset($instructor->discipline_8_fk)) {
            array_push($disciplines, $instructor->discipline8Fk);
        }
        if (isset($instructor->discipline_9_fk)) {
            array_push($disciplines, $instructor->discipline9Fk);
        }
        if (isset($instructor->discipline_10_fk)) {
            array_push($disciplines, $instructor->discipline10Fk);
        }
        if (isset($instructor->discipline_11_fk)) {
            array_push($disciplines, $instructor->discipline11Fk);
        }
        if (isset($instructor->discipline_12_fk)) {
            array_push($disciplines, $instructor->discipline12Fk);
        }
        if (isset($instructor->discipline_13_fk)) {
            array_push($disciplines, $instructor->discipline13Fk);
        }

        return $disciplines;
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionBatchupdate($id)
    {

        //@done S1 - Modificar o banco para ter a relação estrangeira dos professores e turmas
        //@done S1 - Criar Trigger ou solução similar para colocar o auto increment do professor no instructor_fk da turma
        //@done s1 - Atualizar o teachingdata ao atualizar o classroom
        $modelClassroom = $this->loadModel($id, $this->MODEL_CLASSROOM);

        if (!empty($_POST)) {
            $enrollments = $_POST;
            foreach ($enrollments as $id => $fields) {
                $enro = StudentEnrollment::model()->findByPk($id);
                $enro->admission_type = $fields['admission_type'];
                $enro->current_stage_situation = $fields['current_stage_situation'];
                $enro->update(array('admission_type', 'current_stage_situation'));
            }
        }


        $this->render('batchupdate', array(
            'modelClassroom' => $modelClassroom,
        ));
    }

    public function actionBatchUpdateTotal($id)
    {

        $modelClassroom = $this->loadModel($id, $this->MODEL_CLASSROOM);

        if (!empty($_POST)) {
            $enrollments = $_POST;
            foreach ($enrollments as $id => $fields) {
                $enro = StudentEnrollment::model()->findByPk($id);
                $enro->edcenso_stage_vs_modality_fk = $fields['edcenso_stage_vs_modality_fk'];
                $enro->update(array('edcenso_stage_vs_modality_fk'));
            }
        }

        $sql1 = "SELECT id,name FROM edcenso_stage_vs_modality where id in(1, 2, 4, 5, 6, 7, 8, 9, 10, 11, 14, 15, 16, 17, 18, 19, 20, 21,41,39,40,69,70)";
        $stages = Yii::app()->db->createCommand($sql1)->queryAll();
        $optionsStage = [];
        foreach ($stages as $stage) {
            $optionsStage[$stage['id']] = $stage['name'];
        }
        $this->render('batchupdatetotal', array(
            'modelClassroom' => $modelClassroom,
            'options_stage' => $optionsStage
        ));
    }

    public function actionBatchUpdateTransport($id)
    {

        //@done S1 - Modificar o banco para ter a relação estrangeira dos professores e turmas
        //@done S1 - Criar Trigger ou solução similar para colocar o auto increment do professor no instructor_fk da turma
        //@done s1 - Atualizar o teachingdata ao atualizar o classroom
        $modelClassroom = $this->loadModel($id, $this->MODEL_CLASSROOM);

        if (!empty($_POST)) {
            $enrollments = $_POST;
            foreach ($enrollments as $id => $field) {
                if (!empty($field['public_transport'])) {
                    $enro = StudentEnrollment::model()->findByPk($id);
                    $enro->public_transport = '1';
                    $enro->transport_responsable_government = '2';
                    $enro->vehicle_type_bus = '1';
                    $enro->update(array('public_transport', 'transport_responsable_government', 'vehicle_type_bus'));
                } else {
                    $enro = StudentEnrollment::model()->findByPk($id);
                    $enro->public_transport = '0';
                    $enro->transport_responsable_government = '';
                    $enro->vehicle_type_bus = '';
                    $enro->update(array('public_transport', 'transport_responsable_government', 'vehicle_type_bus'));
                }
            }
        }

        $this->render('batchupdatetransport', array(
            'modelClassroom' => $modelClassroom,
        ));
    }

    public function actionBatchupdatEnrollment($id)
    {
        $modelClassroom = $this->loadModel($id, $this->MODEL_CLASSROOM);
        if (!empty($_POST)) {
            $enrollments = $_POST;
            foreach ($enrollments as $eid => $field) {
                if (!empty($field['reenrollment'])) {
                    $enro = StudentEnrollment::model()->findByPk($eid);
                    $enro->reenrollment = '1';
                    $enro->update(array('reenrollment'));
                } else {
                    $enro = StudentEnrollment::model()->findByPk($eid);
                    $enro->reenrollment = '0';
                    $enro->update(array('reenrollment'));
                }
            }
        }


        $classroom = $id;
        $criteria = new CDbCriteria();
        $criteria->alias = 'e';
        $criteria->select = '*';
        $criteria->join = 'JOIN student_identification s ON s.id = e.student_fk';
        $criteria->condition = "classroom_fk = $classroom";
        $criteria->order = 's.name';
        $enrollments = StudentEnrollment::model()->findAll($criteria);

        $this->render('batchupdatenrollment', array(
            'modelClassroom' => $modelClassroom,
            'enrollments' => $enrollments,
        ));
    }

    public function actionCreate()
    {
        $modelClassroom = new Classroom;
        $modelTeachingData = array();

        if (isset($_POST['Classroom']) && isset($_POST['teachingData']) && isset($_POST['disciplines']) && isset($_POST['events'])) {
            $disciplines = json_decode($_POST['disciplines'], true);
            $this->setDisciplines($modelClassroom, $disciplines);

            // Em adição, inserir a condição dos campos 25-35 (AEE activities)
            // de nao deixar criar com todos os campos igual a 0
            if (isset($_POST['Classroom']["complementary_activity_type_1"])) {
                $compActs = $_POST['Classroom']["complementary_activity_type_1"];
            }
            $_POST['Classroom']["complementary_activity_type_1"] = isset($compActs[0]) ? $compActs[0] : null;
            $_POST['Classroom']["complementary_activity_type_2"] = isset($compActs[1]) ? $compActs[1] : null;
            $_POST['Classroom']["complementary_activity_type_3"] = isset($compActs[2]) ? $compActs[2] : null;
            $_POST['Classroom']["complementary_activity_type_4"] = isset($compActs[3]) ? $compActs[3] : null;
            $_POST['Classroom']["complementary_activity_type_5"] = isset($compActs[4]) ? $compActs[4] : null;
            $_POST['Classroom']["complementary_activity_type_6"] = isset($compActs[5]) ? $compActs[5] : null;

            $modelClassroom->attributes = $_POST['Classroom'];
            $modelClassroom->sedsp_sync = 0;
            $modelClassroom->assistance_type = $this->defineAssistanceType($modelClassroom);

            if ($modelClassroom->week_days_sunday || $modelClassroom->week_days_monday || $modelClassroom->week_days_tuesday || $modelClassroom->week_days_wednesday || $modelClassroom->week_days_thursday || $modelClassroom->week_days_friday || $modelClassroom->week_days_saturday) {

                if ($modelClassroom->validate() && $modelClassroom->save()) {
                    $saved = true;
                    $teachingDataValidated = true;

                    $teachingData = json_decode($_POST['teachingData']);

                    foreach ($teachingData as $key => $td) {
                        $modelTeachingData[$key] = new InstructorTeachingData;
                        $modelTeachingData[$key]->classroom_id_fk = $modelClassroom->id;
                        $modelTeachingData[$key]->school_inep_id_fk = $modelClassroom->school_inep_fk;
                        $modelTeachingData[$key]->instructor_fk = $td->Instructor;
                        $modelTeachingData[$key]->role = $td->Role;
                        $modelTeachingData[$key]->contract_type = $td->ContractType;
                        $modelTeachingData[$key]->regent = $td->RegentTeacher;
                        $modelTeachingData[$key]->disciplines = $td->Disciplines;
                        $teachingDataValidated = $teachingDataValidated && $modelTeachingData[$key]->validate();
                    }


                    if ($teachingDataValidated) {
                        foreach ($modelTeachingData as $key => $td) {
                            if ($saved) {
                                $saved = $modelTeachingData[$key]->save();
                                foreach ($td->disciplines as $discipline) {
                                    $curricularMatrix = CurricularMatrix::model()->find("stage_fk = :stage_fk and discipline_fk = :discipline_fk and school_year = :year", ["stage_fk" => $modelClassroom->edcenso_stage_vs_modality_fk, "discipline_fk" => $discipline, "year" => Yii::app()->user->year]);
                                    $teachingMatrixes = new TeachingMatrixes();
                                    $teachingMatrixes->curricular_matrix_fk = $curricularMatrix->id;
                                    $teachingMatrixes->teaching_data_fk = $modelTeachingData[$key]->id;
                                    $teachingMatrixes->save();
                                }
                            }
                        }
                        if ($saved) {

                            if (Yii::app()->features->isEnable("FEAT_SEDSP")) {
                                $loginUseCase = new LoginUseCase();
                                $loginUseCase->checkSEDToken();

                                $result = $modelClassroom->syncToSEDSP("create", "create");
                            } else {
                                $result = ["flash" => "success", "message" => "Turma adicionada com sucesso!"];
                            }

                            Log::model()->saveAction("classroom", $modelClassroom->id, "C", $modelClassroom->name);
                            Yii::app()->user->setFlash($result["flash"], $result["message"]);
                            $this->redirect(array('index'));
                        }
                    }
                }
            } else {
                $modelClassroom->addError('week_days_sunday', Yii::t('default', 'Week Days') . ' ' . Yii::t('default', 'cannot be blank'));
            }
        }


        $this->render('create', array(
            'modelClassroom' => $modelClassroom,
            'complementary_activities' => array(),
            'modelTeachingData' => $modelTeachingData,
            'modelEnrollments' => [],
        ));
    }

    public function actionUpdate($id)
    {
        $modelClassroom = $this->loadModel($id, $this->MODEL_CLASSROOM);
        $modelTeachingData = $this->loadModel($id, $this->MODEL_TEACHING_DATA);
        $studentsEnrollments = $modelClassroom->studentEnrollments;
        $modelEnrollments = [];
        foreach ($studentsEnrollments as $studentEnrollment) {
            $array = [];
            $array["enrollmentId"] = $studentEnrollment->id;
            $array["studentId"] = $studentEnrollment->studentFk->id;
            $array["studentName"] = $studentEnrollment->studentFk->name;
            $array["dailyOrder"] = $studentEnrollment->daily_order;

            if (Yii::app()->features->isEnable("FEAT_SEDSP")) {
                $array["synced"] = $studentEnrollment->studentFk->sedsp_sync && $studentEnrollment->sedsp_sync;
            }

            array_push($modelEnrollments, $array);
        }


        $disableFieldsWhenItsUBATUBA = false;
        if (Yii::app()->features->isEnable("FEAT_SEDSP") && $modelClassroom->gov_id != null && !empty($modelClassroom->studentEnrollments)) {
            $disableFieldsWhenItsUBATUBA = true;
        }

        if (isset($_POST['enrollments']) && isset($_POST['toclassroom'])) {
            $enrollments = $_POST['enrollments'];
            $count_students = count($_POST['enrollments']);
            if (!empty($_POST['toclassroom'])) {
                $class_room = Classroom::model()->findByPk($_POST['toclassroom']);
                foreach ($enrollments as $enrollment) {
                    $enro = StudentEnrollment::model()->findByPk($enrollment);
                    $enro->classroom_fk = $class_room->id;
                    $enro->classroom_inep_id = $class_room->inep_id;
                    $enro->status = 2;
                    $enro->create_date = date('Y-m-d');
                    $enro->update(array('classroom_fk', 'classroom_inep_id', 'status', 'create_date'));
                }
            } else {
                foreach ($enrollments as $enrollment) {
                    $studentEnrollment = StudentEnrollment::model()->findByPk($enrollment);
                    $frequencyAndMean = FrequencyAndMeanByDiscipline::model()
                        ->findAllByAttributes(array('enrollment_fk' => $studentEnrollment->id));
                    $gradeResults = GradeResults::model()
                        ->findAllByAttributes(array('enrollment_fk' => $studentEnrollment->id));
                    $frequencyByExam = FrequencyByExam::model()
                        ->findAllByAttributes(array('enrollment_fk' => $studentEnrollment->id));

                    foreach ($gradeResults as $gradeResult) {
                        $gradeResult->delete();
                    }
                    foreach ($frequencyAndMean as $eachFrequencyAndMean) {
                        $eachFrequencyAndMean->delete();
                    }
                    foreach ($frequencyByExam as $frequencyExam) {
                        $frequencyExam->delete();
                    }
                    $studentEnrollment->delete();
                    Yii::app()->user->setFlash('success', 'Matrículas de alunos excluídas com sucesso');
                }
            }
            $this->redirect(array('index'));
        }
        if (isset($_POST['Classroom']) && isset($_POST['teachingData']) && isset($_POST['disciplines'])) {

            foreach ($modelTeachingData as $key => $td) {
                $td->delete();
            }

            // Em adição, inserir a condição dos campos 25-35 (AEE activities)
            // de nao deixar criar com todos os campos igual a 0
            if (isset($_POST['Classroom']["complementary_activity_type_1"])) {
                $compActs = $_POST['Classroom']["complementary_activity_type_1"];
            }
            $_POST['Classroom']["complementary_activity_type_1"] = isset($compActs[0]) ? $compActs[0] : null;
            $_POST['Classroom']["complementary_activity_type_2"] = isset($compActs[1]) ? $compActs[1] : null;
            $_POST['Classroom']["complementary_activity_type_3"] = isset($compActs[2]) ? $compActs[2] : null;
            $_POST['Classroom']["complementary_activity_type_4"] = isset($compActs[3]) ? $compActs[3] : null;
            $_POST['Classroom']["complementary_activity_type_5"] = isset($compActs[4]) ? $compActs[4] : null;
            $_POST['Classroom']["complementary_activity_type_6"] = isset($compActs[5]) ? $compActs[5] : null;

            $beforeChangeClassroom = new Classroom();
            $beforeChangeClassroom->attributes = $modelClassroom->attributes;
            $modelClassroom->attributes = $_POST['Classroom'];


            $modelClassroom->assistance_type = $this->defineAssistanceType($modelClassroom);


            if (Yii::app()->features->isEnable("FEAT_SEDSP") && !$disableFieldsWhenItsUBATUBA) {

                if (
                    $beforeChangeClassroom->turn != $modelClassroom->turn ||
                    $beforeChangeClassroom->sedsp_acronym != $modelClassroom->sedsp_acronym ||
                    $beforeChangeClassroom->sedsp_classnumber != $modelClassroom->sedsp_classnumber ||
                    $beforeChangeClassroom->sedsp_max_physical_capacity != $modelClassroom->sedsp_max_physical_capacity ||
                    $beforeChangeClassroom->initial_hour != $modelClassroom->initial_hour ||
                    $beforeChangeClassroom->initial_minute != $modelClassroom->initial_minute ||
                    $beforeChangeClassroom->final_hour != $modelClassroom->final_hour ||
                    $beforeChangeClassroom->final_minute != $modelClassroom->final_minute ||
                    $beforeChangeClassroom->week_days_monday != $modelClassroom->week_days_monday ||
                    $beforeChangeClassroom->week_days_tuesday != $modelClassroom->week_days_tuesday ||
                    $beforeChangeClassroom->week_days_wednesday != $modelClassroom->week_days_wednesday ||
                    $beforeChangeClassroom->week_days_thursday != $modelClassroom->week_days_thursday ||
                    $beforeChangeClassroom->week_days_friday != $modelClassroom->week_days_friday ||
                    $beforeChangeClassroom->week_days_saturday != $modelClassroom->week_days_saturday
                ) {

                    $modelClassroom->sedsp_sync = 0;
                }
            }


            $disciplines = json_decode($_POST['disciplines'], true);
            $this->setDisciplines($modelClassroom, $disciplines);
            $hasWeekDaySelected = $modelClassroom->week_days_sunday ||
                $modelClassroom->week_days_monday ||
                $modelClassroom->week_days_tuesday ||
                $modelClassroom->week_days_wednesday ||
                $modelClassroom->week_days_thursday ||
                $modelClassroom->week_days_friday ||
                $modelClassroom->week_days_saturday;

            if (!$hasWeekDaySelected) {
                $modelClassroom->addError('week_days_sunday', Yii::t('default', 'Week Days') . ' ' . Yii::t('default', 'cannot be blank'));
            }

            if ($hasWeekDaySelected && $modelClassroom->validate() && $modelClassroom->save()) {
                $saved = true;
                $teachingDataValidated = true;

                $teachingData = json_decode($_POST['teachingData']);

                foreach ($teachingData as $key => $td) {
                    $modelTeachingData[$key] = new InstructorTeachingData;
                    $modelTeachingData[$key]->classroom_id_fk = $modelClassroom->id;
                    $modelTeachingData[$key]->school_inep_id_fk = $modelClassroom->school_inep_fk;
                    $modelTeachingData[$key]->instructor_fk = $td->Instructor;
                    $modelTeachingData[$key]->role = $td->Role;
                    $modelTeachingData[$key]->contract_type = $td->ContractType;
                    $modelTeachingData[$key]->regent = $td->RegentTeacher;
                    $modelTeachingData[$key]->disciplines = $td->Disciplines;
                    $teachingDataValidated = $teachingDataValidated && $modelTeachingData[$key]->validate();
                }

                if ($teachingDataValidated) {
                    foreach ($modelTeachingData as $key => $td) {
                        if ($saved) {
                            $saved = $modelTeachingData[$key]->save();
                            foreach ($td->disciplines as $discipline) {
                                $curricularMatrix = CurricularMatrix::model()->find(
                                    "stage_fk = :stage_fk and discipline_fk = :discipline_fk and school_year = :year",
                                    [
                                        "stage_fk" => $modelClassroom->edcenso_stage_vs_modality_fk,
                                        "discipline_fk" => $discipline,
                                        "year" => Yii::app()->user->year
                                    ]
                                );
                                $teachingMatrixes = new TeachingMatrixes();
                                $teachingMatrixes->curricular_matrix_fk = $curricularMatrix->id;
                                $teachingMatrixes->teaching_data_fk = $modelTeachingData[$key]->id;
                                $teachingMatrixes->save();
                            }
                        }
                    }
                    if ($saved) {
                        if (Yii::app()->features->isEnable("FEAT_SEDSP") && !$modelClassroom->sedsp_sync) {
                            $loginUseCase = new LoginUseCase();
                            $loginUseCase->checkSEDToken();

                            $inConsultaTurmaClasse = new InConsultaTurmaClasse(
                                Yii::app()->user->year,
                                $modelClassroom->gov_id
                            );
                            $dataSource = new ClassroomSEDDataSource();
                            $outConsultaTurmaClasse = $dataSource->getConsultClass($inConsultaTurmaClasse);

                            if (!property_exists($outConsultaTurmaClasse, "outErro")) {
                                $result = $modelClassroom->syncToSEDSP("edit", $outConsultaTurmaClasse->outAnoLetivo != null ? "edit" : "create");
                            } else {
                                $result = ["flash" => "error", "message" => $outConsultaTurmaClasse->outErro];
                            }
                        } else {
                            $result = ["flash" => "success", "message" => "Turma atualizada com sucesso!"];
                        }

                        Log::model()->saveAction("classroom", $modelClassroom->id, "U", $modelClassroom->name);
                        Yii::app()->user->setFlash($result["flash"], $result["message"]);
                        $this->redirect(array('index'));
                    }
                }
            }
        }


        $this->render('update', array(
            'modelClassroom' => $modelClassroom,
            'modelTeachingData' => $modelTeachingData,
            'modelEnrollments' => $modelEnrollments,
            'disabledFields' => $disableFieldsWhenItsUBATUBA
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    //@done s1 - excluir Matriculas, TeachingData e Turma
    public function actionDelete($id)
    {
        $classroom = $this->loadModel($id, $this->MODEL_CLASSROOM);
        $teachingDatas = $this->loadModel($id, $this->MODEL_TEACHING_DATA);
        $ableToDelete = true;
        if (Yii::app()->features->isEnable("FEAT_SEDSP")) {
            if ($classroom->gov_id !== null) {
                $loginUseCase = new LoginUseCase();
                $loginUseCase->checkSEDToken();

                $inConsultaTurmaClasse = new InConsultaTurmaClasse(
                    Yii::app()->user->year,
                    $classroom->gov_id
                );
                $dataSource = new ClassroomSEDDataSource();
                $outConsultaTurmaClasse = $dataSource->getConsultClass($inConsultaTurmaClasse);

                if (!property_exists($outConsultaTurmaClasse, "outErro")) {
                    $inExcluirTurmaClasse = new InExcluirTurmaClasse($classroom->gov_id);
                    $result = $dataSource->excluirTurmaClasse($inExcluirTurmaClasse);
                    if ($result->outErro !== null) {
                        $ableToDelete = false;
                        $erro = $result->outErro;
                    }
                } else {
                    $ableToDelete = false;
                    $erro = $outConsultaTurmaClasse->outErro;
                }
            }
        }
        if ($ableToDelete) {
            try {
                foreach ($teachingDatas as $teachingData) {
                    $teachingData->delete();
                }
                if ($classroom->delete()) {
                    Log::model()->saveAction("classroom", $id, "D", $classroom->name);
                    echo json_encode(["valid" => true, "message" => "Turma excluída com sucesso!"]);
                }
            } catch (Exception $e) {
                echo json_encode(["valid" => false, "message" => "Não se pode remover turma com professores vinculados."]);
            }
        } else {
            echo json_encode(["valid" => false, "message" => "Não foi possível remover a turma no SEDSP. Motivo: " . $erro]);
        }
    }

    public function actionSyncToSedsp($id)
    {
        $modelClassroom = Classroom::model()->findByPk($id);

        $loginUseCase = new LoginUseCase();
        $loginUseCase->checkSEDToken();

        $inConsultaTurmaClasse = new InConsultaTurmaClasse(
            Yii::app()->user->year,
            $modelClassroom->gov_id
        );
        $dataSource = new ClassroomSEDDataSource();
        $outConsultaTurmaClasse = $dataSource->getConsultClass($inConsultaTurmaClasse);

        if (!property_exists($outConsultaTurmaClasse, "outErro")) {
            $result = $modelClassroom->syncToSEDSP("edit", $outConsultaTurmaClasse->outAnoLetivo != null ? "edit" : "create");
        } else {
            $result = ["flash" => "error", "message" => $outConsultaTurmaClasse->outErro];
        }

        Yii::app()->user->setFlash($result["flash"], $result["message"]);
        $this->redirect(array('index'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $dataProvider = Classroom::model()->with('enrollmentsCount')->search();

        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model = new Classroom('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Classroom'])) {
            $model->attributes = $_GET['Classroom'];
        }

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id, $model)
    {
        $return = null;

        if ($model == $this->MODEL_CLASSROOM) {
            $return = Classroom::model()->findByPk($id);
            $complementaryActivitiesArray = [];
            if ($return->complementary_activity_type_1 != null) {
                array_push($complementaryActivitiesArray, $return->complementary_activity_type_1);
            }
            if ($return->complementary_activity_type_2 != null) {
                array_push($complementaryActivitiesArray, $return->complementary_activity_type_2);
            }
            if ($return->complementary_activity_type_3 != null) {
                array_push($complementaryActivitiesArray, $return->complementary_activity_type_3);
            }
            if ($return->complementary_activity_type_4 != null) {
                array_push($complementaryActivitiesArray, $return->complementary_activity_type_4);
            }
            if ($return->complementary_activity_type_5 != null) {
                array_push($complementaryActivitiesArray, $return->complementary_activity_type_5);
            }
            if ($return->complementary_activity_type_6 != null) {
                array_push($complementaryActivitiesArray, $return->complementary_activity_type_6);
            }
            $return->complementary_activity_type_1 = $complementaryActivitiesArray;
        } elseif ($model == $this->MODEL_TEACHING_DATA) {
            $classroom = $id;
            $instructors = InstructorTeachingData::model()->findAll('classroom_id_fk = ' . $classroom);
            $return = $instructors;
        } elseif ($model == $this->MODEL_STUDENT_ENROLLMENT) {
            $classroom = $id;
            $student = StudentEnrollment::model()->findAll('classroom_fk = ' . $classroom);
            $return = $student;
        }

        if ($return === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $return;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'classroom-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionUpdateTime()
    {
        $return = array('first' => ":", 'last' => ":");
        if (isset($_POST['Classroom']['turn'])) {
            $turn = $_POST['Classroom']['turn'];
            $config = SchoolConfiguration::model()->findByAttributes(array('school_inep_id_fk' => Yii::app()->user->school));
            if ($turn == "M") {
                $return['first'] = $config->morning_initial;
                $return['last'] = $config->morning_final;
            } elseif ($turn == "T") {
                $return['first'] = $config->afternoom_initial;
                $return['last'] = $config->afternoom_final;
            } elseif ($turn == "N") {
                $return['first'] = $config->night_initial;
                $return['last'] = $config->night_final;
            } elseif ($turn == "I") {
                $return['first'] = $config->allday_initial;
                $return['last'] = $config->allday_final;
            }
        }
        echo json_encode($return);
    }

    public function actionUpdateDisciplines()
    {
        $disciplines = Yii::app()->db->createCommand("
            select ed.id, ed.name from curricular_matrix cm
            join edcenso_discipline ed on ed.id = cm.discipline_fk
            where cm.stage_fk = :id and cm.school_year = :year")
            ->bindParam(":id", $_POST["id"])->bindParam(":year", Yii::app()->user->year)->queryAll();
        if ($disciplines) {
            echo json_encode(["valid" => true, "disciplines" => $disciplines]);
        } else {
            echo json_encode(["valid" => false]);
        }
    }

    public function actionChangeEnrollments()
    {

        $ids = $_POST['list'];
        $enrollments = StudentEnrollment::model()->findAllByPk($ids);

        usort($enrollments, function ($a, $b) use ($ids) {
            $posA = array_search($a->id, $ids);
            $posB = array_search($b->id, $ids);
            return $posA - $posB;
        });

        foreach ($enrollments as $i => $enrollment) {
            $enrollment->daily_order = $i + 1;
            $enrollment->save();
        }
        ;
        $result = array_map(function ($enrollment) {
            return [
                "id" => $enrollment->id, "name" => $enrollment->studentFk->name,
                "daily_order" => $enrollment->daily_order
            ];
        }, $enrollments);

        echo  json_encode($result);
    }

    public function actionSyncUnsyncedStudents()
    {
        $loginUseCase = new LoginUseCase();
        $loginUseCase->checkSEDToken();

        $classroom = Classroom::model()->findByPk($_POST["classroomId"]);
        $result = [];
        foreach ($classroom->studentEnrollments as $studentEnrollment) {
            $studentIdentification = $studentEnrollment->studentFk;
            if (!$studentIdentification->sedsp_sync || !$studentEnrollment->sedsp_sync) {
                $response = $studentIdentification->syncStudentWithSED($studentIdentification->id, $studentEnrollment, self::UPDATE);
                if ($response["identification"]->outErro !== null || $response["enrollment"]->outErro !== null) {
                    array_push($result, [
                        "enrollmentId" => $studentEnrollment->id,
                        "valid" => false,
                        "studentName" => $studentIdentification->name,
                        "identificationMessage" => $response["identification"] != null ? $response["identification"]->outErro : null,
                        "enrollmentMessage" => $response["enrollment"] != null ? $response["enrollment"]->outErro : null
                    ]);
                } else {
                    array_push($result, [
                        "enrollmentId" => $studentEnrollment->id,
                        "valid" => true
                    ]);
                }
            }
        }
        echo json_encode($result);
    }
}
