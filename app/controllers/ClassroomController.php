<?php
/*
 * 
 */

//-----------------------------------------CLASSE VALIDADA ATÉ A SEQUENCIA 35!!------------------------
class ClassroomController extends Controller
{

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
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('index', 'view', 'create', 'update', 'getassistancetype',
                    'updateassistancetypedependencies', 'updatecomplementaryactivity',
                    'getcomplementaryactivitytype', 'delete',
                    'updateTime', 'move', 'batchupdate', 'batchupdatetotal', 'batchupdatetransport', 'updateDisciplines'
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

    private  function defineAssistanceType($classroom){
        $is_aee = $classroom['aee'];
        $is_complementary_activity = $classroom['complementary_activity'];
        $is_schooling = $classroom['schooling'];

        if(isset($is_aee) && $is_aee){
            return 5;
        }
        if(isset($is_complementary_activity) && $is_complementary_activity){
            return 4;
        }
        if(isset($is_schooling) && $is_schooling){
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
        } else if ($at == 2 || $at == 3) {
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

    static function classroomDisciplineLabelArray()
    {
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
        $labels[10007] = yii::t('default', 'Listen, Speak, Thought and Imagination');
        $labels[10008] = yii::t('default', 'Space, Time, Quantity, Relations and Transformations');
        $labels[10009] = yii::t('default', 'Body, Gesture and Movement');
        $labels[10010] = yii::t('default', 'Traces, Sounds, Colors and Shapes');
        $labels[10011] = yii::t('default', 'The I, the Other and the We');

        return $labels;
    }

    static function classroomDisciplineLabelResumeArray()
    {
        $labels = array();
        $labels[1] = "Química";
        $labels[2] = "Física";
        $labels[3] = "Matemática";
        $labels[4] = "Biologia";
        $labels[5] = "Ciências";
        $labels[6] = "Português";
        $labels[7] = "Inglês";
        $labels[8] = "Espanhol";
        $labels[9] = "Outro Idioma";
        $labels[10] = "Artes";
        $labels[11] = "Edicação Física";
        $labels[12] = "História";
        $labels[13] = "Geografia";
        $labels[14] = "Filosofia";
        $labels[16] = "Informática";
        $labels[17] = "Disc. Profissionalizante";
        $labels[20] = "Educação Especial";
        $labels[21] = "Sociedade&nbsp;e Cultura";
        $labels[23] = "Libras";
        $labels[25] = "Pedogogia";
        $labels[26] = "Ensino Religioso";
        $labels[27] = "Língua Nativa";
        $labels[28] = "Estudo Social";
        $labels[29] = "Sociologia";
        $labels[30] = "Francês";
        $labels[99] = "Outras";
        $labels[10001] = yii::t('default', 'Writing');

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
        $disciplines[25] = $classroom->discipline_pedagogical;
        $disciplines[26] = $classroom->discipline_religious;
        $disciplines[27] = $classroom->discipline_native_language;
        $disciplines[28] = $classroom->discipline_social_study;
        $disciplines[29] = $classroom->discipline_sociology;
        $disciplines[30] = $classroom->discipline_foreign_language_franch;
        $disciplines[99] = $classroom->discipline_others;

        return $disciplines;
    }

    //@done s1 - criar função para transformas as Disciplinas do TeachingData em Array

    static function teachingDataDiscipline2array($instructor)
    {
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

    public function actionBatchupdatetotal($id)
    {

        //@done S1 - Modificar o banco para ter a relação estrangeira dos professores e turmas
        //@done S1 - Criar Trigger ou solução similar para colocar o auto increment do professor no instructor_fk da turma
        //@done s1 - Atualizar o teachingdata ao atualizar o classroom
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
        foreach ($stages as $index => $stage) {
            $options_stage[$stage['id']] = $stage['name'];
        }
        $this->render('batchupdatetotal', array(
            'modelClassroom' => $modelClassroom,
            'options_stage' => $options_stage
        ));
    }

    public function actionBatchupdatetransport($id)
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
                            Log::model()->saveAction("classroom", $modelClassroom->id, "C", $modelClassroom->name);
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

    public function actionUpdate($id)
    {
        $modelClassroom = $this->loadModel($id, $this->MODEL_CLASSROOM);
        $modelTeachingData = $this->loadModel($id, $this->MODEL_TEACHING_DATA);

        if (isset($_POST['enrollments']) && isset($_POST['toclassroom'])) {
            $enrollments = $_POST['enrollments'];
            $count_students = count($_POST['enrollments']);
            if (!empty($_POST['toclassroom'])) {
                $class_room = Classroom::model()->findByPk($_POST['toclassroom']);
                foreach ($enrollments as $enrollment) {
                    $enro = StudentEnrollment::model()->findByPk($enrollment);
                    $enro->classroom_fk = $class_room->id;
                    $enro->classroom_inep_id = $class_room->inep_id;
                    $enro->update(array('classroom_fk', 'classroom_inep_id'));
                }
            } else {
                foreach ($enrollments as $enrollment) {
                    $enro = StudentEnrollment::model()->findByPk($enrollment);
                    $enro->delete();
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

            $modelClassroom->attributes = $_POST['Classroom'];
            $modelClassroom->assistance_type = $this->defineAssistanceType($modelClassroom);

            $disciplines = json_decode($_POST['disciplines'], true);
            $this->setDisciplines($modelClassroom, $disciplines);

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
                            Log::model()->saveAction("classroom", $modelClassroom->id, "U", $modelClassroom->name);
                            Yii::app()->user->setFlash('success', Yii::t('default', 'Turma atualizada com sucesso!'));
                            $this->redirect(array('index'));
                        }
                    }
                }
            } else {
                $modelClassroom->addError('week_days_sunday', Yii::t('default', 'Week Days') . ' ' . Yii::t('default', 'cannot be blank'));
            }
        }


        $this->render('update', array(
            'modelClassroom' => $modelClassroom,
            'modelTeachingData' => $modelTeachingData,
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
        try {
            foreach($teachingDatas as $teachingData) {
                $teachingData->delete();
            }
            if ($classroom->delete()) {
                Log::model()->saveAction("classroom", $id, "D", $classroom->name);
                Yii::app()->user->setFlash('success', Yii::t('default', 'Turma excluída com sucesso!'));
                $this->redirect(array('index'));
            }
        } catch (Exception $e) {
            throw new CHttpException(901, "Não se pode remover turma com professores vinculados.");
        }
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $dataProvider = Classroom::model()->search();
    
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

    public function actionUpdateDisciplines()
    {
        $disciplines = Yii::app()->db->createCommand("
            select ed.id, ed.name from curricular_matrix cm 
            join edcenso_discipline ed on ed.id = cm.discipline_fk
            where cm.stage_fk = :id and cm.school_year = :year")->bindParam(":id", $_POST["id"])->bindParam(":year", Yii::app()->user->year)->queryAll();
        if ($disciplines) {
            echo json_encode(["valid" => true, "disciplines" => $disciplines]);
        } else {
            echo json_encode(["valid" => false]);
        }
    }
}
