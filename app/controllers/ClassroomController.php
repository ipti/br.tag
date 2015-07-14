<?php

//-----------------------------------------CLASSE VALIDADA ATÉ A SEQUENCIA 35!!------------------------
class ClassroomController extends Controller {

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
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('index', 'view', 'create', 'update', 'getassistancetype',
                    'updateassistancetypedependencies', 'updatecomplementaryactivity',
                    'getcomplementaryactivitytype', 'delete',
                    'addLesson', 'updateLesson', 'removeDraggedLesson', 'deleteLesson', 'getClassBoard',
                    'updateTime'
                ),
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin'),
                'users' => array('admin'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionGetAssistanceType() {
        $classroom = new Classroom();
        $classroom->attributes = $_POST['Classroom'];
        $schoolStructure = SchoolStructure::model()->findByPk($classroom->school_inep_fk);
        $result = array('html' => '', 'val' => '');

        $result['html'] = CHtml::tag('option', array('value' => null), CHtml::encode('Selecione o tipo de atendimento'), true);
        if ($schoolStructure != null) {

            $encode = array(
                0 => CHtml::encode('Não se Aplica'),
                1 => CHtml::encode('Classe Hospitalar'),
                2 => CHtml::encode('Unidade de Internação Socioeducativa'),
                3 => CHtml::encode('Unidade Prisional'),
                4 => CHtml::encode('Atividade Complementar'),
                5 => CHtml::encode('Atendimento Educacional Especializado (AEE)'));

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

    public function actionUpdateComplementaryActivity() {
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

        echo CHtml::tag('option', array('value' => 'null'), CHtml::encode('Selecione a atividade complementar'), true);
        foreach ($data as $value => $name) {
            echo CHtml::tag('option', array('value' => $value), CHtml::encode($name), true);
        }
    }

    public function actionUpdateAssistanceTypeDependencies() {
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
        $result['Modality'] = CHtml::tag('option', array('value' => null), CHtml::encode('Selecione a modalidade'), true);

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
        } else if ($at == 2 || $at == 3) {
            $data = EdcensoStageVsModality::model()->findAll('id!=1 && id!=2 && id!=3 && id!=56 ' . $where);
        } else {
            $data = array();
            $result['StageEmpty'] = true;
        }

        $data = CHtml::listData($data, 'id', 'name');

        $result['Stage'] = CHtml::tag('option', array('value' => null), 'Selecione o estágio vs modalidade', true);

        foreach ($data as $value => $name) {
            $result['Stage'] .= CHtml::tag('option', array('value' => $value, $classroom->edcenso_stage_vs_modality_fk == $value ? "selected" : "deselected" => $classroom->edcenso_stage_vs_modality_fk == $value ? "selected" : "deselected"), CHtml::encode($name), true);
        }

        echo json_encode($result);
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    public function setDisciplines($modelClassroom, $discipline) {
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
        $modelClassroom->discipline_religious = $putNull ? null : (isset($discipline[25]) ? $discipline[25] : 0);
        $modelClassroom->discipline_native_language = $putNull ? null : (isset($discipline[26]) ? $discipline[26] : 0);
        $modelClassroom->discipline_pedagogical = $putNull ? null : (isset($discipline[27]) ? $discipline[27] : 0);
        $modelClassroom->discipline_social_study = $putNull ? null : (isset($discipline[28]) ? $discipline[28] : 0);
        $modelClassroom->discipline_sociology = $putNull ? null : (isset($discipline[29]) ? $discipline[29] : 0);
        $modelClassroom->discipline_foreign_language_franch = $putNull ? null : (isset($discipline[30]) ? $discipline[30] : 0);
        $modelClassroom->discipline_others = $putNull ? null : (isset($discipline[99]) ? $discipline[99] : 0);
    }

    //@done s1 - criar função para pegar os labels das disciplinas separando pelo id do educacenso

    static function classroomDisciplineLabelArray() {
        $labels = array();
        $labels[1] = Classroom::model()->attributeLabels()['discipline_chemistry'];
        $labels[2] = Classroom::model()->attributeLabels()['discipline_physics'];
        $labels[3] = Classroom::model()->attributeLabels()['discipline_mathematics'];
        $labels[4] = Classroom::model()->attributeLabels()['discipline_biology'];
        $labels[5] = Classroom::model()->attributeLabels()['discipline_science'];
        $labels[6] = Classroom::model()->attributeLabels()['discipline_language_portuguese_literature'];
        $labels[7] = Classroom::model()->attributeLabels()['discipline_foreign_language_english'];
        $labels[8] = Classroom::model()->attributeLabels()['discipline_foreign_language_spanish'];
        $labels[9] = Classroom::model()->attributeLabels()['discipline_foreign_language_other'];
        $labels[10] = Classroom::model()->attributeLabels()['discipline_arts'];
        $labels[11] = Classroom::model()->attributeLabels()['discipline_physical_education'];
        $labels[12] = Classroom::model()->attributeLabels()['discipline_history'];
        $labels[13] = Classroom::model()->attributeLabels()['discipline_geography'];
        $labels[14] = Classroom::model()->attributeLabels()['discipline_philosophy'];
        $labels[16] = Classroom::model()->attributeLabels()['discipline_informatics'];
        $labels[17] = Classroom::model()->attributeLabels()['discipline_professional_disciplines'];
        $labels[20] = Classroom::model()->attributeLabels()['discipline_special_education_and_inclusive_practices'];
        $labels[21] = Classroom::model()->attributeLabels()['discipline_sociocultural_diversity'];
        $labels[23] = Classroom::model()->attributeLabels()['discipline_libras'];
        $labels[25] = Classroom::model()->attributeLabels()['discipline_pedagogical'];
        $labels[26] = Classroom::model()->attributeLabels()['discipline_religious'];
        $labels[27] = Classroom::model()->attributeLabels()['discipline_native_language'];
        $labels[28] = Classroom::model()->attributeLabels()['discipline_social_study'];
        $labels[29] = Classroom::model()->attributeLabels()['discipline_sociology'];
        $labels[30] = Classroom::model()->attributeLabels()['discipline_foreign_language_franch'];
        $labels[99] = Classroom::model()->attributeLabels()['discipline_others'];
        $labels[10001] = yii::t('default', 'Writing');

        return $labels;
    }

    //@done s1 - criar função para transformar as disciplinas do Classroom em Array

    public static function classroomDiscipline2array($classroom) {
        $disciplines = array();

        $disciplines[1] = $classroom->discipline_chemistry;
        $disciplines[2] = $classroom->discipline_physics;
        $disciplines[3] = $classroom->discipline_mathematics;
        $disciplines[4] = $classroom->discipline_biology;
        $disciplines[5] = $classroom->discipline_science;
        $disciplines[6] = $classroom->discipline_language_portuguese_literature;
        $disciplines[7] = $classroom->discipline_foreign_language_english;
        $disciplines[8] = $classroom->discipline_foreign_language_spanish;
        $disciplines[9] = $classroom->discipline_foreign_language_other;
        $disciplines[10] = $classroom->discipline_arts;
        $disciplines[11] = $classroom->discipline_physical_education;
        $disciplines[12] = $classroom->discipline_history;
        $disciplines[13] = $classroom->discipline_geography;
        $disciplines[14] = $classroom->discipline_philosophy;
        $disciplines[16] = $classroom->discipline_informatics;
        $disciplines[17] = $classroom->discipline_professional_disciplines;
        $disciplines[20] = $classroom->discipline_special_education_and_inclusive_practices;
        $disciplines[21] = $classroom->discipline_sociocultural_diversity;
        $disciplines[23] = $classroom->discipline_libras;
        $disciplines[25] = $classroom->discipline_religious;
        $disciplines[26] = $classroom->discipline_native_language;
        $disciplines[27] = $classroom->discipline_pedagogical;
        $disciplines[28] = $classroom->discipline_social_study;
        $disciplines[29] = $classroom->discipline_sociology;
        $disciplines[30] = $classroom->discipline_foreign_language_franch;
        $disciplines[99] = $classroom->discipline_others;

        return $disciplines;
    }

    //@done s1 - criar função para transformas as Disciplinas do TeachingData em Array

    static function teachingDataDiscipline2array($instructor) {
        $disciplines = array();

        if (isset($instructor->discipline_1_fk))
            array_push($disciplines, $instructor->discipline1Fk);
        if (isset($instructor->discipline_2_fk))
            array_push($disciplines, $instructor->discipline2Fk);
        if (isset($instructor->discipline_3_fk))
            array_push($disciplines, $instructor->discipline3Fk);
        if (isset($instructor->discipline_4_fk))
            array_push($disciplines, $instructor->discipline4Fk);
        if (isset($instructor->discipline_5_fk))
            array_push($disciplines, $instructor->discipline5Fk);
        if (isset($instructor->discipline_6_fk))
            array_push($disciplines, $instructor->discipline6Fk);
        if (isset($instructor->discipline_7_fk))
            array_push($disciplines, $instructor->discipline7Fk);
        if (isset($instructor->discipline_8_fk))
            array_push($disciplines, $instructor->discipline8Fk);
        if (isset($instructor->discipline_9_fk))
            array_push($disciplines, $instructor->discipline9Fk);
        if (isset($instructor->discipline_10_fk))
            array_push($disciplines, $instructor->discipline10Fk);
        if (isset($instructor->discipline_11_fk))
            array_push($disciplines, $instructor->discipline11Fk);
        if (isset($instructor->discipline_12_fk))
            array_push($disciplines, $instructor->discipline12Fk);
        if (isset($instructor->discipline_13_fk))
            array_push($disciplines, $instructor->discipline13Fk);
        return $disciplines;
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        //@done s1 - salvar o teachingdata junto com o classroom
        //@done s1 - retornar os valores do teachingdata caso exista algum erro

        $modelClassroom = new Classroom;
        $modelTeachingData = array();

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Classroom']) && isset($_POST['teachingData']) && isset($_POST['disciplines']) && isset($_POST['events'])) {
            $teachingData = json_decode($_POST['teachingData']);
            $disciplines = json_decode($_POST['disciplines'], true);
            $events = json_decode($_POST['events'], true);


            foreach ($teachingData as $key => $td) {

                $modelTeachingData[$key] = new InstructorTeachingData;
                $modelTeachingData[$key]->classroom_id_fk = $td->Classroom;
                $modelTeachingData[$key]->school_inep_id_fk = Yii::app()->user->school;
                $modelTeachingData[$key]->instructor_fk = $td->Instructor;
                $modelTeachingData[$key]->role = $td->Role;
                $modelTeachingData[$key]->contract_type = $td->ContractType;

                $modelTeachingData[$key]->discipline_1_fk = isset($td->Disciplines[0]) ? $td->Disciplines[0] : NULL;
                $modelTeachingData[$key]->discipline_2_fk = isset($td->Disciplines[1]) ? $td->Disciplines[1] : NULL;
                $modelTeachingData[$key]->discipline_3_fk = isset($td->Disciplines[2]) ? $td->Disciplines[2] : NULL;
                $modelTeachingData[$key]->discipline_4_fk = isset($td->Disciplines[3]) ? $td->Disciplines[3] : NULL;
                $modelTeachingData[$key]->discipline_5_fk = isset($td->Disciplines[4]) ? $td->Disciplines[4] : NULL;
                $modelTeachingData[$key]->discipline_6_fk = isset($td->Disciplines[5]) ? $td->Disciplines[5] : NULL;
                $modelTeachingData[$key]->discipline_7_fk = isset($td->Disciplines[6]) ? $td->Disciplines[6] : NULL;
                $modelTeachingData[$key]->discipline_8_fk = isset($td->Disciplines[7]) ? $td->Disciplines[7] : NULL;
                $modelTeachingData[$key]->discipline_9_fk = isset($td->Disciplines[8]) ? $td->Disciplines[8] : NULL;
                $modelTeachingData[$key]->discipline_10_fk = isset($td->Disciplines[9]) ? $td->Disciplines[9] : NULL;
                $modelTeachingData[$key]->discipline_11_fk = isset($td->Disciplines[10]) ? $td->Disciplines[10] : NULL;
                $modelTeachingData[$key]->discipline_12_fk = isset($td->Disciplines[11]) ? $td->Disciplines[11] : NULL;
                $modelTeachingData[$key]->discipline_13_fk = isset($td->Disciplines[12]) ? $td->Disciplines[12] : NULL;
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

            $modelClassroom->attributes = $_POST['Classroom'];

            $this->setDisciplines($modelClassroom, $disciplines);

            if ($modelClassroom->week_days_sunday || $modelClassroom->week_days_monday || $modelClassroom->week_days_tuesday || $modelClassroom->week_days_wednesday || $modelClassroom->week_days_thursday || $modelClassroom->week_days_friday || $modelClassroom->week_days_saturday) {

                if ($modelClassroom->validate() && $modelClassroom->save()) {
                    $save = true;
                    $validate = true;

                    foreach ($modelTeachingData as $key => $td) {
                        $modelTeachingData[$key]->classroom_id_fk = $modelClassroom->id;
                        $modelTeachingData[$key]->school_inep_id_fk = $modelClassroom->school_inep_fk;
                        $validate = $validate && $modelTeachingData[$key]->validate();
                    }

                    if ($validate) {
                        foreach ($modelTeachingData as $key => $td) {
                            $save = $save && $modelTeachingData[$key]->save();
                        }
                        if ($save) {
                            foreach ($events as $e) {
                                $e['classroom'] = $modelClassroom->id;
                                $this->actionAddLesson($e);
                            }
                            Yii::app()->user->setFlash('success', Yii::t('default', 'Turma adicionada com sucesso!'));
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
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {

        //@done S1 - Modificar o banco para ter a relação estrangeira dos professores e turmas
        //@done S1 - Criar Trigger ou solução similar para colocar o auto increment do professor no instructor_fk da turma
        //@done s1 - Atualizar o teachingdata ao atualizar o classroom

        $modelClassroom = $this->loadModel($id, $this->MODEL_CLASSROOM);
        $modelTeachingData = $this->loadModel($id, $this->MODEL_TEACHING_DATA);
        if (isset($_POST['Classroom']) && isset($_POST['teachingData']) && isset($_POST['disciplines'])) {
            $teachingData = json_decode($_POST['teachingData']);
            $disciplines = json_decode($_POST['disciplines'], true);

            $mtdLength = count($modelTeachingData);

            foreach ($modelTeachingData as $key => $td) {
                $td->delete();
            }

            foreach ($teachingData as $key => $td) {
                $update = false;

                $modelTeachingData[$key] = new InstructorTeachingData;
                $modelTeachingData[$key]->instructor_fk = $td->Instructor;

                $modelTeachingData[$key]->role = $td->Role;
                $modelTeachingData[$key]->contract_type = $td->ContractType;

                $modelTeachingData[$key]->discipline_1_fk = isset($td->Disciplines[0]) ? $td->Disciplines[0] : NULL;
                $modelTeachingData[$key]->discipline_2_fk = isset($td->Disciplines[1]) ? $td->Disciplines[1] : NULL;
                $modelTeachingData[$key]->discipline_3_fk = isset($td->Disciplines[2]) ? $td->Disciplines[2] : NULL;
                $modelTeachingData[$key]->discipline_4_fk = isset($td->Disciplines[3]) ? $td->Disciplines[3] : NULL;
                $modelTeachingData[$key]->discipline_5_fk = isset($td->Disciplines[4]) ? $td->Disciplines[4] : NULL;
                $modelTeachingData[$key]->discipline_6_fk = isset($td->Disciplines[5]) ? $td->Disciplines[5] : NULL;
                $modelTeachingData[$key]->discipline_7_fk = isset($td->Disciplines[6]) ? $td->Disciplines[6] : NULL;
                $modelTeachingData[$key]->discipline_8_fk = isset($td->Disciplines[7]) ? $td->Disciplines[7] : NULL;
                $modelTeachingData[$key]->discipline_9_fk = isset($td->Disciplines[8]) ? $td->Disciplines[8] : NULL;
                $modelTeachingData[$key]->discipline_10_fk = isset($td->Disciplines[9]) ? $td->Disciplines[9] : NULL;
                $modelTeachingData[$key]->discipline_11_fk = isset($td->Disciplines[10]) ? $td->Disciplines[10] : NULL;
                $modelTeachingData[$key]->discipline_12_fk = isset($td->Disciplines[11]) ? $td->Disciplines[11] : NULL;
                $modelTeachingData[$key]->discipline_13_fk = isset($td->Disciplines[12]) ? $td->Disciplines[12] : NULL;
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

            $modelClassroom->attributes = $_POST['Classroom'];

            $this->setDisciplines($modelClassroom, $disciplines);

            if ($modelClassroom->week_days_sunday || $modelClassroom->week_days_monday || $modelClassroom->week_days_tuesday || $modelClassroom->week_days_wednesday || $modelClassroom->week_days_thursday || $modelClassroom->week_days_friday || $modelClassroom->week_days_saturday) {

                if ($modelClassroom->validate() && $modelClassroom->save()) {
                    $save = true;
                    $validate = true;

                    foreach ($modelTeachingData as $key => $td) {
                        $modelTeachingData[$key]->classroom_id_fk = $modelClassroom->id;
                        $modelTeachingData[$key]->school_inep_id_fk = $modelClassroom->school_inep_fk;

                        $validate = $validate && $modelTeachingData[$key]->validate();
                    }
                    if ($validate) {
                        foreach ($modelTeachingData as $key => $td) {
                            $save = $save && $modelTeachingData[$key]->save();
                        }
                        if ($save) {
                            Yii::app()->user->setFlash('success', Yii::t('default', 'Turma atualizada com sucesso!'));
                            $this->redirect(array('index'));
                        }
                    }
                }
            } else {
                $modelClassroom->addError('week_days_sunday', Yii::t('default', 'Week Days') . ' ' . Yii::t('default', 'cannot be blank'));
            }
        }
        $compActs = array();
        if (isset($modelClassroom->complementary_activity_type_1))
            array_push($compActs, $modelClassroom->complementary_activity_type_1);
        if (isset($modelClassroom->complementary_activity_type_2))
            array_push($compActs, $modelClassroom->complementary_activity_type_2);
        if (isset($modelClassroom->complementary_activity_type_3))
            array_push($compActs, $modelClassroom->complementary_activity_type_3);
        if (isset($modelClassroom->complementary_activity_type_4))
            array_push($compActs, $modelClassroom->complementary_activity_type_4);
        if (isset($modelClassroom->complementary_activity_type_5))
            array_push($compActs, $modelClassroom->complementary_activity_type_5);
        if (isset($modelClassroom->complementary_activity_type_6))
            array_push($compActs, $modelClassroom->complementary_activity_type_6);


        $this->render('update', array(
            'modelClassroom' => $modelClassroom,
            'modelTeachingData' => $modelTeachingData,
            'complementaryActivities' => $compActs
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    //@done s1 - excluir Matriculas, TeachingData e Turma
    public function actionDelete($id) {
        $students = $this->loadModel($id, $this->MODEL_STUDENT_ENROLLMENT);
        $instructors = $this->loadModel($id, $this->MODEL_TEACHING_DATA);
        foreach ($students as $key => $value) {
            $value->delete();
        }
        foreach ($instructors as $key => $value) {
            $value->delete();
        }

        if ($this->loadModel($id, $this->MODEL_CLASSROOM)->delete()) {
            Yii::app()->user->setFlash('success', Yii::t('default', 'Turma excluída com sucesso!'));
            $this->redirect(array('index'));
        } else {
            throw new CHttpException(404, 'A página requisitada não existe.');
        }
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $filter = new Classroom('search');
        $filter->unsetAttributes();  // clear any default values
        if (isset($_GET['Classroom'])) {
            $filter->attributes = $_GET['Classroom'];
        }
        $dataProvider = new CActiveDataProvider('Classroom', array('pagination' => array(
                'pageSize' => 12,
        )));
        $this->render('index', array(
            'dataProvider' => $dataProvider,
            'filter' => $filter,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Classroom('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Classroom']))
            $model->attributes = $_GET['Classroom'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id, $model) {
        $return = null;

        if ($model == $this->MODEL_CLASSROOM) {
            $return = Classroom::model()->findByPk($id);
        } else if ($model == $this->MODEL_TEACHING_DATA) {
            $classroom = $id;
            $instructors = InstructorTeachingData::model()->findAll('classroom_id_fk = ' . $classroom);
            $return = $instructors;
        } else if ($model == $this->MODEL_STUDENT_ENROLLMENT) {
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
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'classroom-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionDeleteLesson($lesson = null) {
        $lesson = ($lesson == null) ? $_POST['lesson'] : $lesson;

        $days = isset($_POST['days']) ? $_POST['days'] : 0;
        $minutes = isset($_POST['minutes']) ? $_POST['minutes'] : 0;

        $classboard = ClassBoard::model()->findByPk($lesson['db']);

        $initial_timestamp = strtotime($lesson['start']);
        $final_timestamp = empty($lesson['end']) ? -1 : strtotime($lesson['end']);

        $day = date('w', $initial_timestamp);
        $day -= $days;
        $schedule_initial = date('G', $initial_timestamp);
        $schedule_initial -= $minutes / 60;
        $schedule_final = ($final_timestamp == -1) ? ($schedule_initial + 1) : date('G', $final_timestamp);

        //Pega a semana em forma de matriz
        $week = $this->getSchedule($classboard);

        $schedule = $week[$day];
        //Percorre o intervalo dos horários selecionados
        //removendo os que não existem mais
        for ($i = $schedule_initial; $i < $schedule_final; $i++) {
            foreach ($schedule as $key => $value) {
                if ($value == $i) {
                    unset($schedule[$key]);
                }
            }
        }

        //Coloca o horário de volta em forma de string
        //para então colocar de volta no objeto
        $schedule = implode(';', $schedule);
        switch ($day) {
            case 0: $classboard->week_day_sunday = $schedule;
                break;
            case 1: $classboard->week_day_monday = $schedule;
                break;
            case 2: $classboard->week_day_tuesday = $schedule;
                break;
            case 3: $classboard->week_day_wednesday = $schedule;
                break;
            case 4: $classboard->week_day_thursday = $schedule;
                break;
            case 5: $classboard->week_day_friday = $schedule;
                break;
            case 6: $classboard->week_day_saturday = $schedule;
                break;
        }

        return ($classboard->validate() && $classboard->save());
    }

    public function actionUpdateLesson() {
        $lesson = $_POST['lesson'];
        return $this->actionDeleteLesson($lesson) && $this->actionAddLesson($lesson);
    }

    public function actionAddLesson($lesson = null) {
        $lesson = ($lesson == null) ? $_POST['lesson'] : $lesson;
        $classroom = $lesson['classroom'];
        $discipline = $lesson['discipline'];
        $instructor = $lesson['instructor'];
        $classboard = ClassBoard::model()->find("classroom_fk = $classroom and discipline_fk =$discipline");

        $pinitial = explode('GMT', $lesson['start']);
        $lesson['start'] = $pinitial[0];
        $initial_timestamp = strtotime($lesson['start']);

        $pfinal = explode('GMT', $lesson['end']);
        $lesson['end'] = $pfinal[0];
        $final_timestamp = empty($lesson['end']) ? -1 : strtotime($lesson['end']);

        $schedule_initial = date('G', $initial_timestamp);
        $schedule_final = ($final_timestamp == -1) ? ($schedule_initial + 1) : date('G', $final_timestamp);

        $week_day = date('w', $initial_timestamp);

        if ($classboard == null) {
            $classboard = new ClassBoard;
            $classboard->classroom_fk = $classroom;
            $classboard->discipline_fk = $discipline;
            $classboard->instructor_fk = $instructor;

            $schedule = array();
        } else {
            switch ($week_day) {
                case 0: $schedule = $classboard->week_day_sunday;
                    break;
                case 1: $schedule = $classboard->week_day_monday;
                    break;
                case 2: $schedule = $classboard->week_day_tuesday;
                    break;
                case 3: $schedule = $classboard->week_day_wednesday;
                    break;
                case 4: $schedule = $classboard->week_day_thursday;
                    break;
                case 5: $schedule = $classboard->week_day_friday;
                    break;
                case 6: $schedule = $classboard->week_day_saturday;
                    break;
            }
            $schedule = $schedule == '0' ? array() : explode(';', $schedule);
        }

        for ($i = $schedule_initial; $i < $schedule_final; $i++) {
            array_push($schedule, $i);
        }
        $schedule = array_unique($schedule);
        $schedule = implode(';', $schedule);

        switch ($week_day) {
            case 0: $classboard->week_day_sunday = $schedule;
                break;
            case 1: $classboard->week_day_monday = $schedule;
                break;
            case 2: $classboard->week_day_tuesday = $schedule;
                break;
            case 3: $classboard->week_day_wednesday = $schedule;
                break;
            case 4: $classboard->week_day_thursday = $schedule;
                break;
            case 5: $classboard->week_day_friday = $schedule;
                break;
            case 6: $classboard->week_day_saturday = $schedule;
                break;
        }

        if ($classboard->validate() && $classboard->save()) {
            $lesson['title'] = $classboard->disciplineFk->name;
            $instructorName = $classboard->instructor_fk == null ? 'Sem Instrutor' : $classboard->instructorFk->name;
            $event = array(
                'id' => $lesson['id'],
                'db' => $classboard->id,
                'title' => (strlen($lesson['title']) > 30 ? substr($lesson['title'], 0, 27) . "..." : $lesson['title']) . " - " . $instructorName,
                'discipline' => $lesson['discipline'],
                'classroom' => $lesson['classroom'],
                'instructor' => $lesson['instructor'],
                'start' => $lesson['start'],
                'end' => $lesson['end'],
            );
            echo json_encode($event);
            return true;
        } else {
            return false;
        }
    }

    private function getSchedule($classboard) {
        $schedule = array(
            explode(';', $classboard->week_day_sunday),
            explode(';', $classboard->week_day_monday),
            explode(';', $classboard->week_day_tuesday),
            explode(';', $classboard->week_day_wednesday),
            explode(';', $classboard->week_day_thursday),
            explode(';', $classboard->week_day_friday),
            explode(';', $classboard->week_day_saturday),
        );
        return $schedule;
    }

    public function actionUpdateTime() {
        $return = array('first' => ":", 'last' => ":");
        if (isset($_POST['Classroom']['turn'])) {
            $turn = $_POST['Classroom']['turn'];
            $config = SchoolConfiguration::model()->findByAttributes(array('school_inep_id_fk' => Yii::app()->user->school));
            if ($turn == "M") {
                $return['first'] = $config->morning_initial;
                $return['last'] = $config->morning_final;
            } else if ($turn == "T") {
                $return['first'] = $config->afternoom_initial;
                $return['last'] = $config->afternoom_final;
            } else if ($turn == "N") {
                $return['first'] = $config->night_initial;
                $return['last'] = $config->night_final;
            } else if ($turn == "I") {
                $return['first'] = $config->allday_initial;
                $return['last'] = $config->allday_final;
            }
        }
        echo json_encode($return);
    }

    public function actionGetClassBoard($classroom_fk = null) {
        $year = 1996;
        $month = 1;

        if ((!isset($_POST['ClassBoard']['classroom_fk']) || empty($_POST['ClassBoard']['classroom_fk'])) && $classroom_fk == null)
            return null;
        $classroom = $classroom_fk == null ? $_POST['ClassBoard']['classroom_fk'] : $classroom_fk;
        $classboard = ClassBoard::model()->findAll("classroom_fk = $classroom");
        $lessons = 0;

        $events = array();
        foreach ($classboard as $cb) {
            $discipline = $cb->disciplineFk;
            $week = $this->getSchedule($cb);
            $title = $discipline->name;
            $instructorName = $cb->instructor_fk == null ? 'Sem Instrutor' : $cb->instructorFk->name;
            foreach ($week as $day => $d) {
                foreach ($d as $schedule) {
                    if ($schedule != 0) {
                        $event = array(
                            'id' => ++$lessons,
                            'db' => $cb->id,
                            'title' => (strlen($title) > 30 ? substr($title, 0, 37) . "..." : $title) . ' - ' . $instructorName,
                            'discipline' => $discipline->id,
                            'classroom' => $classroom,
                            'instructor' => $cb->instructor_fk,
                            'start' => date(DateTime::ISO8601, mktime($schedule, 0, 0, $month, $day == 0 ? 7 : $day, $year))
                        );
                        array_push($events, $event);
                    }
                }
            }
        }
        echo json_encode($events);
    }

}
