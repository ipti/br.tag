<?php

class CourseplanController extends Controller
{

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = 'fullmenu';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
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
                    'create',
                    'update',
                    'index',
                    'deletePlan',
                    'getDisciplines',
                    'save',
                    'getCourseClasses',
                    'getAbilities',
                    'getAbilitiesInitialStructure',
                    'getAbilitiesNextStructure',
                    'addResources',
                    'getResources',
                    'pendingPlans',
                    'validatePlan',
                    'enableCoursePlanEdition',
                    'checkResourceExists'
                ),
                'users' => array('*'),
            ),
            array(
                'deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $coursePlan = new CoursePlan();
        if (isset($_POST['CoursePlan'])) {
            $this->actionSave();
            TLog::info("Plano de Aula criado com sucesso");
        } else {
            $resources = CourseClassResources::model()->findAll(array('order' => 'name'));
            $this->render('create', array(
                'coursePlan' => $coursePlan,
                'stages' => $this->getStages(),
                'resources' => $resources,
            ));
            Yii::app()->end();
        }
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        if (isset($_POST['CoursePlan'])) {
            $this->actionSave($id);
            TLog::info("Plano de aula atualizado com sucesso.", ["CoursePlan" => $id]);
        }
        if (!isset($_POST['CoursePlan'])) {
            $coursePlan = $this->loadModel($id);
            $resources = CourseClassResources::model()->findAll(array('order' => 'name'));

            $this->render('update', array(
                'coursePlan' => $coursePlan,
                'stages' => $this->getStages(),
                'resources' => $resources,
            ));
        }
    }

    private function getStages()
    {
        if (Yii::app()->getAuthManager()->checkAccess('instructor', Yii::app()->user->loginInfos->id)) {
            $stages = Yii::app()->db->createCommand(
                "select esvm.id, esvm.name from edcenso_stage_vs_modality esvm
                join curricular_matrix cm on cm.stage_fk = esvm.id
                join teaching_matrixes tm on tm.curricular_matrix_fk = cm.id
                join instructor_teaching_data itd on itd.id = tm.teaching_data_fk
                join instructor_identification ii on ii.id = itd.instructor_fk
                where ii.users_fk = :userid and school_year = :year order by esvm.name"
            )->bindParam(":userid", Yii::app()->user->loginInfos->id)->bindParam(":year", Yii::app()->user->year)->queryAll();
        } else {
            $stages = Yii::app()->db->createCommand("select esvm.id, esvm.name from edcenso_stage_vs_modality esvm join curricular_matrix cm on cm.stage_fk = esvm.id where school_year = :year order by esvm.name")->bindParam(":year", Yii::app()->user->year)->queryAll();
        }
        return $stages;
    }

    public function getInstructors()
    {
        $sqlCommand = "
            SELECT DISTINCT u.id, u.name  FROM course_plan cp
            LEFT JOIN users u ON u.id = cp.users_fk
            WHERE cp.situation = 'PENDENTE'";

        return Yii::app()->db->createCommand($sqlCommand)->queryAll();
    }


    public function actionGetCourseClasses()
    {
        $coursePlan = CoursePlan::model()->findByPk($_POST["coursePlanId"]);
        $courseClasses = [];
        $courseClassesIds = [];
        foreach ($coursePlan->courseClasses as $courseClass) {
            $order = $courseClass->order - 1;
            $courseClasses[$order]["class"] = $courseClass->order;
            $courseClasses[$order]['courseClassId'] = $courseClass->id;
            $courseClasses[$order]['content'] = $courseClass->content;
            $courseClasses[$order]['methodology'] = $courseClass->methodology;
            $courseClasses[$order]['resources'] = [];
            $courseClasses[$order]['abilities'] = [];

            foreach ($courseClass->courseClassHasClassResources as $courseClassHasClassResource) {
                $resource["id"] = $courseClassHasClassResource->id;
                $resource["value"] = $courseClassHasClassResource->course_class_resource_fk;
                $resource["description"] = $courseClassHasClassResource->courseClassResourceFk->name;
                $resource["amount"] = $courseClassHasClassResource->amount;
                $courseClasses[$order]['resources'][] = $resource;
            }

            foreach ($courseClass->courseClassHasClassAbilities as $courseClassHasClassAbility) {
                $ability["id"] = $courseClassHasClassAbility->courseClassAbilityFk->id;
                $ability["code"] = $courseClassHasClassAbility->courseClassAbilityFk->code;
                $ability["description"] = $courseClassHasClassAbility->courseClassAbilityFk->description;
                $ability["discipline"] = $courseClassHasClassAbility->courseClassAbilityFk->edcensoDisciplineFk->name;
                $courseClasses[$order]['abilities'][] = $ability;
            }

            $courseClasses[$order]["deleteButton"] = empty($courseClass->classContents) ? "" : "js-unavailable";

            $courseClassesIds[] = $courseClass->id;
        }

        // Log e saída JSON com a estrutura original mantida
        TLog::info("Listagem de aulas por plano de aula.", [
            "CoursePlan" => $coursePlan->id,
            "CourseClasses" => $courseClassesIds
        ]);

        echo json_encode(["data" => array_values($courseClasses)]);
    }

    public function actionGetDisciplines()
    {
        $result = [];
        $isMinorEducation = TagUtils::isStageChildishEducation($_POST["stage"]);
        $disciplinesLabels = ClassroomController::classroomDisciplineLabelArray();
        if (Yii::app()->getAuthManager()->checkAccess('instructor', Yii::app()->user->loginInfos->id)) {
            $disciplines = Yii::app()->db->createCommand(
                "select ed.id from teaching_matrixes tm
                join instructor_teaching_data itd on itd.id = tm.teaching_data_fk
                join instructor_identification ii on ii.id = itd.instructor_fk
                join curricular_matrix cm on cm.id = tm.curricular_matrix_fk
                join edcenso_discipline ed on ed.id = cm.discipline_fk
                where ii.users_fk = :userid and cm.stage_fk = :stage_fk and school_year = :year order by ed.name"
            )
                ->bindParam(":userid", Yii::app()->user->loginInfos->id)->bindParam(":stage_fk", $_POST["stage"])->bindParam(":year", Yii::app()->user->year)->queryAll();
            foreach ($disciplines as $discipline) {
                array_push($result, ["id" => $discipline['id'], "name" => CHtml::encode($disciplinesLabels[$discipline['id']]), "isMinorEducation" => $isMinorEducation]);
            }
            TLog::info("Listagem de disciplina por etapa de ensino com filtro de usuário de professor.", ["Stage" => $_POST["stage"], "UserInstructor" => Yii::app()->user->loginInfos->id]);
        } else {
            $disciplines = Yii::app()->db->createCommand("select curricular_matrix.discipline_fk from curricular_matrix join edcenso_discipline ed on ed.id = curricular_matrix.discipline_fk where stage_fk = :stage_fk and school_year = :year order by ed.name")->bindParam(":stage_fk", $_POST["stage"])->bindParam(":year", Yii::app()->user->year)->queryAll();
            foreach ($disciplines as $i => $discipline) {
                if (isset($discipline['discipline_fk'])) {
                    array_push($result, ["id" => $discipline['discipline_fk'], "name" => CHtml::encode($disciplinesLabels[$discipline['discipline_fk']]), "isMinorEducation" => $isMinorEducation]);
                }
            }
            TLog::info("Listagem de disciplina por etapa de ensino.", ["Stage" => $_POST["stage"]]);
        }
        echo json_encode($result);
    }

    public function actionGetAbilities()
    {

        $disciplineId = Yii::app()->request->getPost("discipline");
        $stage = Yii::app()->request->getPost("stage");

        $criteria = new CDbCriteria();
        $criteria->alias = "cca";
        $criteria->join = "join edcenso_stage_vs_modality esvm on esvm.id = cca.edcenso_stage_vs_modality_fk";
        $criteria->condition = "code is not null and cca.edcenso_stage_vs_modality_fk = :stage";
        $criteria->params = [":stage" => $stage];

        $abilities = [];

        if ($disciplineId != null) {
            $criteria->condition .= "cca.edcenso_discipline_fk = :discipline";
            $criteria->params[":discipline"] = $disciplineId;
        }

        $abilities = CourseClassAbilities::model()->findAll($criteria);

        $formattedAbilities = [];
        foreach ($abilities as $ability) {
            $formattedAbilities[] = [
                "id" => $ability->id,
                "code" => $ability->code,
                "description" => $ability->description
            ];
        }

        echo CJSON::encode($formattedAbilities);
    }

    public function actionGetAbilitiesInitialStructure()
    {

        $disciplineId = Yii::app()->request->getPost("discipline");

        $criteria = new CDbCriteria();
        $criteria->alias = "cca";
        $criteria->join = "join edcenso_stage_vs_modality esvm on esvm.id = cca.edcenso_stage_vs_modality_fk";

        $abilities = [];

        if ($disciplineId != null) {
            $criteria->condition = "cca.edcenso_discipline_fk = :discipline and parent_fk is null";
            $criteria->params = [":discipline" => $disciplineId];
            $abilities = CourseClassAbilities::model()->findAll($criteria);
        }

        $result = [];
        $result["options"] = [];
        foreach ($abilities as $i => $ability) {
            if ($i == 0) {
                $result["selectTitle"] = $ability["type"];
            }
            array_push($result["options"], ["id" => $ability->id, "code" => $ability->code, "description" => $ability->description]);
        }

        echo CJSON::encode($result);
    }

    public function actionGetAbilitiesNextStructure()
    {
        $parentId = Yii::app()->request->getPost("id");
        $abilities = CourseClassAbilities::model()->findAll("parent_fk = :parent_fk", [":parent_fk" => $parentId]);
        $result = [];
        $result["options"] = [];
        foreach ($abilities as $i => $ability) {
            if ($i == 0) {
                $result["selectTitle"] = $ability["type"];
            }
            array_push($result["options"], ["id" => $ability->id, "code" => $ability->code, "description" => $ability->description]);
        }

        echo CJSON::encode($result);
    }

    /**
     * Sabe the Course Plan, and yours course classes.
     */
    public function actionSave($id = null)
    {
        // $transaction = Yii::app()->db->beginTransaction();
        $request = Yii::app()->request->getPost("CoursePlan");
        try {
            if ($id !== null) {
                $coursePlan = CoursePlan::model()->findByPk($id);
                $logSituation = "U";
            } else {
                $coursePlan = new CoursePlan;
                $coursePlan->school_inep_fk = Yii::app()->user->school;
                $coursePlan->users_fk = Yii::app()->user->loginInfos->id;
                $logSituation = "C";
            }
            $startTimestamp = $this->dataConverter($request["start_date"], 0);
            $request["start_date"] = $startTimestamp;
            $coursePlan->attributes = $request;
            $coursePlan->situation = 'PENDENTE';
            if ($coursePlan->save()) {
                TLog::info("Plano de aula salvo com sucesso", ['CoursePlanId' => $coursePlan->id]);
            }
            $errors = $coursePlan->getErrors();
            $courseClassIds = [];
            $i = 1;
            foreach ($_POST["course-class"] as $cc) {
                if ($cc["id"] == "") {
                    $courseClass = new CourseClass;
                    $courseClass->course_plan_fk = $coursePlan->id;
                } else {
                    $courseClass = CourseClass::model()->findByPk($cc["id"]);
                }
                $courseClass->order = $i++;
                $courseClass->content = $cc['content'];
                $courseClass->methodology = $cc['methodology'];
                if ($courseClass->save()) {
                    TLog::info("Aula salva com sucesso", ['CourseClassId' => $courseClass->id, 'CoursePlanId' => $coursePlan->id]);
                }

                $courseClassIds[] = $courseClass->id;

                $abilitiesMerged = is_array($cc['ability']) ? implode("', '", $cc['ability']): $cc['ability'];
                CourseClassHasClassAbility::model()->deleteAll("course_class_fk = :course_class_fk and course_class_ability_fk not in ( '" . $abilitiesMerged . "' )", [":course_class_fk" => $courseClass->id]);
                foreach ($cc["ability"] as $abilityId) {
                    $courseClassHasClassAbility = CourseClassHasClassAbility::model()->find("course_class_fk = :course_class_fk and course_class_ability_fk = :course_class_ability_fk", ["course_class_fk" => $courseClass->id, "course_class_ability_fk" => $abilityId]);
                    if ($courseClassHasClassAbility == null) {
                        $courseClassHasClassAbility = new CourseClassHasClassAbility();
                        $courseClassHasClassAbility->course_class_fk = $courseClass->id;
                        $courseClassHasClassAbility->course_class_ability_fk = $abilityId;
                        if ($courseClassHasClassAbility->save()) {
                            TLog::info("CourseClassHasClassAbility salvo com sucesso", ['CourseClassId' => $courseClass->id]);
                        }
                        $coursePlanVsAbility = new CoursePlanDisciplineVsAbilities();
                        $coursePlanVsAbility->course_plan_fk = $coursePlan->id;
                        $abilitieData = CourseClassAbilities::model()->findByPk($abilityId);
                        $coursePlanVsAbility->discipline_fk = $abilitieData->edcenso_discipline_fk;
                        $coursePlanVsAbility->course_class_fk = $courseClass->id;
                        $coursePlanVsAbility->ability_fk = $abilityId;
                        if ($coursePlanVsAbility->save()) {
                            TLog::info("CoursePlanDisciplineVsAbilites salvo com sucesso", ['CourseClassId' => $courseClass->id]);
                        }
                    }
                }

                if ($cc["resource"] != null) {
                    $idsArray = [];
                    foreach ($cc["resource"] as $r) {
                        $courseClassHasClassResource = CourseClassHasClassResource::model()->find("id = :id", ["id" => $r["id"]]);
                        if ($courseClassHasClassResource == null) {
                            $courseClassHasClassResource = new CourseClassHasClassResource();
                            $courseClassHasClassResource->course_class_fk = $courseClass->id;
                            $courseClassHasClassResource->course_class_resource_fk = $r["value"];
                        }
                        $courseClassHasClassResource->amount = $r["amount"];
                        if ($courseClassHasClassResource->save())
                            TLog::info("CourseClassHasClassResource salvo com sucesso", ['CourseClassId' => $courseClass->id]);
                        array_push($idsArray, $courseClassHasClassResource->id);
                    }
                    CourseClassHasClassResource::model()->deleteAll("course_class_fk = :course_class_fk and id not in ( '" . implode("', '", $idsArray) . "' )", [":course_class_fk" => $courseClass->id]);
                    TLog::info("Todos os recusos não relacionados a alguma habilidade foram deletados com sucesso", $idsArray);
                } else {
                    CourseClassHasClassResource::model()->deleteAll("course_class_fk = :course_class_fk", [":course_class_fk" => $courseClass->id]);
                    TLog::info("Todos os recursos foram deletados com sucesso", ['CoursePlanId' => $coursePlan->id, 'CourseClassId' => $courseClass->id]);
                }
            }

            if (empty($courseClassIds)) {
                CourseClass::model()->deleteAll("course_plan_fk = :course_plan_fk", [":course_plan_fk" => $coursePlan->id]);
                TLog::info("Todas as aulas foram deletadas com sucesso.", ['coursePlanId' => $coursePlan->id, 'CourseClasses' => $courseClassIds]);
            } else {
                CourseClass::model()->deleteAll("course_plan_fk = :course_plan_fk and id not in ( '" . implode("', '", $courseClassIds) . "' )", [":course_plan_fk" => $coursePlan->id]);
                TLog::info("Todas as aulas não inclusas na atualização foram deletadas com sucesso.", ['coursePlanId' => $coursePlan->id, 'CourseClassesIds' => $courseClassIds]);
            }
            // $transaction->commit();
            header('HTTP/1.1 200 OK');
            Log::model()->saveAction("courseplan", $id, $logSituation, $coursePlan->name);
            Yii::app()->user->setFlash('success', Yii::t('default', 'Plano de Curso salvo com sucesso!'));
            $this->redirect(array('index'));
        } catch (Exception $e) {
            TLog::error('Ocorreu um erro durante a transação de salvar um plano de aula', $e);
            // $transaction->rollback();
            throw new Exception($e->getMessage(), 500, $e);
        }
    }

    public function actionAddResources()
    {
        // $transaction = Yii::app()->db->beginTransaction();
        try {
            $resources = Yii::app()->request->getPost('resources');
            foreach ($resources as $resource) {
                $newResource = new CourseClassResources();
                $newResource->name = $resource;
                $newResource->save();
            }
            // $transaction->commit();
            header('HTTP/1.1 200 OK');
        } catch (Exception $e) {
            // $transaction->rollback();
            throw new CHttpException(500, $e->getMessage());
        }
    }

    public static function dataConverter($data, $case)
    {
        // Caso 0: converte dd/mm/yyyy para yyyy-mm-dd
        if ($case == 0) {
            $dataObj = date_create_from_format('d/m/Y', $data);
            if (!$dataObj == false)
                return date_format($dataObj, 'Y-m-d');
        }

        // Caso 1: converte yyyy-mm-dd para dd/mm/yyyy
        if ($case == 1) {
            $dataObj = date_create_from_format('Y-m-d G:i:s', $data);
            if (!$dataObj == false)
                return date_format($dataObj, 'd/m/Y');
        }

        return false;
    }

    public function actionGetResources()
    {
        $resources = CourseClassResources::model()->findAll();
        $resources = CHtml::listData($resources, 'id', 'name');
        $options = array();
        foreach ($resources as $value => $name) {
            array_push(
                $options,
                CHtml::tag(
                    'option',
                    ['value' => $value],
                    CHtml::encode($name),
                    true
                )
            );
        }
        echo CJSON::encode($options);
    }

    /**
     * Delete model.
     */

    public function actionDeletePlan($id)
    {
        $coursePlan = $this->loadModel($id);
        TLog::info("Inicia o processo de exclusão do plano de aula");
        $isUsed = false;
        foreach ($coursePlan->courseClasses as $courseClass) {
            if (!empty($courseClass->classContents)) {
                $isUsed = true;
                break;
            }
        }
        if (!$isUsed) {
            TLog::info("Plano de aula não está sendo utilizado", ["id" => $id]);
            // $transaction = Yii::app()->db->beginTransaction();
            try {
                $coursePlan->delete();
                // $transaction->commit();
            } catch (Exception $e) {
                TLog::error("Error ao excluir plano de aula", ["id" => $id, "error" => $e->getMessage()]);
                // $transaction->rollback();
                throw new Exception($e->getMessage(), 500, $e);
            }

            Log::model()->saveAction("courseplan", $id, "D", $coursePlan->name);
            Yii::app()->user->setFlash('success', Yii::t('default', 'Plano de aula excluído com sucesso!'));
        } else {
            TLog::info("Plano de aula está sendo utilizado", ["id" => $id]);
            Yii::app()->user->setFlash('error', Yii::t('default', 'Não se pode remover um plano de aula utilizado em alguma turma.'));
        }
        $this->redirect(array('index'));
    }

    /**
     * Lists all models.
     */

    public function actionIndex()
    {
        $stageRequest = Yii::app()->request->getPost('stage');
        $disciplineRequest = Yii::app()->request->getPost('discipline');

        TLog::info("Listagem de plano de aula");

        $criteria = new CDbCriteria();

        if (isset($disciplineRequest) && $disciplineRequest != "") {
            if (Yii::app()->getAuthManager()->checkAccess('instructor', Yii::app()->user->loginInfos->id)) {

                $criteria->condition = 'users_fk=' . Yii::app()->user->loginInfos->id .
                        ' AND school_inep_fk=' . Yii::app()->user->school .
                        ' AND modality_fk=' . $stageRequest .
                        ' AND discipline_fk=' . $disciplineRequest;

                TLog::info("Listagem de planos de aula para acesso de professor com filtro de disciplina", ["UserInstructor" => Yii::app()->user->loginInfos->id]);
            }
            if (!Yii::app()->getAuthManager()->checkAccess('instructor', Yii::app()->user->loginInfos->id)) {

                $criteria->condition = 'school_inep_fk=' . Yii::app()->user->school .
                        ' AND modality_fk=' . $stageRequest .
                        ' AND discipline_fk=' . $disciplineRequest;

                TLog::info("Listagem de planos de aula para acesso de administrador com filtro de disciplina");
            }

            $dataProvider = new CActiveDataProvider('CoursePlan', array(
                'criteria' => $criteria,
                'pagination' => false
            ));

            $this->renderPartial('_table', array(
                'dataProvider' => $dataProvider,
            ));
            Yii::app()->end();
        }

        if (isset($stageRequest)) {

            if (Yii::app()->getAuthManager()->checkAccess('instructor', Yii::app()->user->loginInfos->id)) {
                $criteria->condition = 'users_fk=' . Yii::app()->user->loginInfos->id .
                    ' AND school_inep_fk=' . Yii::app()->user->school .
                    ' AND modality_fk=' . $stageRequest;

                TLog::info("Listagem de planos de aula para acesso de professor com filtro de etapa", ["UserInstructor" => Yii::app()->user->loginInfos->id]);
            }

            if (!Yii::app()->getAuthManager()->checkAccess('instructor', Yii::app()->user->loginInfos->id)) {
                $criteria->condition = 'school_inep_fk=' . Yii::app()->user->school .' AND modality_fk=' . $stageRequest;
                TLog::info("Listagem de planos de aula para acesso de administrador com filtro de etapa");
            }


            $dataProvider = new CActiveDataProvider('CoursePlan', array(
                'criteria' => $criteria,
                'pagination' => false
            ));

            $this->renderPartial('_table', array(
                'dataProvider' => $dataProvider,
            ));

            Yii::app()->end();
        }

        $this->render('index', array(
            'stages' => $this->getStages()
        ));
    }

    public function actionPendingPlans()
    {
        // Get data requests
        $instructorRequest = Yii::app()->request->getPost('instructor');
        $stageRequest = Yii::app()->request->getPost('stage');
        $disciplineRequest = Yii::app()->request->getPost('discipline');

        $criteria = new CDbCriteria;

        // Starting Array of Tokens to Bound in Criteria
        // Applying Filter To School
        $tokenParams = [':school' => Yii::app()->user->school];

        // Apply Filter to Situation and School
        $criteria->condition = "situation = 'PENDENTE' AND school_inep_fk = :school";

        // Apply Filter to Instructor
        if(isset($instructorRequest) && $instructorRequest != ""){
            $criteria->condition .= " AND users_fk = :user";
            $tokenParams = array_merge($tokenParams, [':user' => $instructorRequest]);
        }

        // Apply filter to Stage
        if(isset($stageRequest) && $stageRequest != ""){
            $criteria->condition .= " AND modality_fk = :stage";
            $tokenParams = array_merge($tokenParams, [':stage' => $stageRequest]);
        }

        // Apply filter to Discipline
        if(isset($disciplineRequest) && $disciplineRequest != ""){
            $criteria->condition .= " AND discipline_fk = :discipline";
            $tokenParams = array_merge($tokenParams, [':discipline' => $disciplineRequest]);
        }

        // Change Params
        $criteria->params = $tokenParams;

        // Create Data provider
        $dataProvider = new CActiveDataProvider('CoursePlan', array(
            'criteria' => $criteria,
            'pagination' => false
        ));

        // Send Data to Select in index page
        if(
            !isset($instructorRequest) &&
            !isset($stageRequest)
        ){
            $instructors = $this->getInstructors();
            $stages = $this->getStages();
            $this->render('pendingPlans', array(
                'instructors' => $instructors,
                'stages' => $stages,
                // 'dataProvider' => $dataProvider,
            ));
            Yii::app()->end();
        }

        if(
            !isset($instructorRequest)
        ){
            $this->actionGetDisciplines();
            Yii::app()->end();
        }

        // Render Table
        $this->renderPartial('_table_pendingPlans', array(
            'dataProvider' => $dataProvider,
        ));
        Yii::app()->end();
    }

    public function actionValidatePlan($id)
    {
        $requestApproval = Yii::app()->request->getPost("approval_field");
        $requestObservation = Yii::app()->request->getPost("observation");
        $coursePlan = $this->loadModel($id);
        if (!isset($requestApproval)) {
            $this->render('formValidate', array(
                'coursePlan' => $coursePlan,
                'stages' => $this->getStages(),
            ));
            TLog::info("Informações de formulário de validação renderizadas.", ["CoursePlan" => $coursePlan->id]);
        }
        if (isset($requestApproval)) {
            if ($requestApproval == "true") {
                $coursePlan->situation = 'APROVADO';
            }
            $coursePlan->observation = $requestObservation;
            if ($coursePlan->save()) {
                TLog::info("Aprovação de plano de aula salvo com sucesso.", ["CoursePlan" => $coursePlan->id, "Status" => $coursePlan->situation]);
            }
        }
    }

    public function actionEnableCoursePlanEdition($id)
    {
        TLog::info("Liberando plano de aula para edição");
        $coursePlan = $this->loadModel($id);
        $coursePlan->situation = 'PENDENTE';
        try {
            $coursePlan->save();
            TLog::info("Plano de aula liberado para edição", ["id" => $id]);
            Yii::app()->user->setFlash('success', Yii::t('default', 'Plano de Curso Liberado para Edição!'));
            $this->redirect(array('index'));
        } catch (Exception $e) {
            TLog::error("Error ao liberar plano de aula para edição", ["id" => $id, "error" => $e->getMessage()]);
            throw new Exception($e->getMessage(), 500, $e);
        }
    }

    public function actionCheckResourceExists()
    {
        $resource = Yii::app()->request->getPost('resource');
        $existingResources = CourseClassResources::model()->findAllByAttributes(array('name' => $resource));
        if ($existingResources == NULL) {
            echo json_encode(["valid" => true]);
            Yii::app()->end();
        }
        echo json_encode(["valid" => false]);
        Yii::app()->end();
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return CoursePlan the loaded model
     * @throws CHttpException
     */
    public

        function loadModel(
        $id
    ) {
        $model = CoursePlan::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CoursePlan $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'course-plan-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
