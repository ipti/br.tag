<?php

class SchoolController extends Controller
{
    //@done s1 - Recuperar endereço pelo CEP
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = 'fullmenu';
    private $schoolIdentification = 'SchoolIdentification';
    private $schoolStucture = 'SchoolStructure';
    private $managerIdentification = 'ManagerIdentification';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return [
            'accessControl', // perform access control for CRUD operations
        ];
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return [
            [
                'allow',
                // allow authenticated user to perform 'create' and 'update' actions
                'actions' => [
                    'edcenso_import',
                    'configacl',
                    'index',
                    'view',
                    'update',
                    'create',
                    'getcities',
                    'getmanagercities',
                    'getdistricts',
                    'getorgans',
                    'updateufdependencies',
                    'updatecitydependencies',
                    'displayLogo',
                    'RemoveLogo'
                ],
                'users' => ['@'],
            ],
            [
                'allow',
                // allow admin user to perform 'admin' and 'delete' actions
                'actions' => ['admin', 'manager', 'delete', 'reports', 'ReportsMonthlyTransaction', 'Record'],
                'users' => ['@'],
            ],
            [
                'deny',
                // deny all users
                'users' => ['*'],
            ],
        ];
    }

    public function actionUpdateCityDependencies()
    {
        $school = new SchoolIdentification();
        $school->attributes = $_POST[$this->schoolIdentification];

        $city = $school->edcenso_city_fk;

        $result = ['District' => ''];
        $result['District'] = $this->actionGetDistricts($city);

        echo json_encode($result);
    }

    //@done s1 - Funcionalidade de atualização dos Distritos e dos Órgãos Regionáis de Educação
    public function actionUpdateUfDependencies()
    {
        $school = new SchoolIdentification();
        $school->attributes = $_POST[$this->schoolIdentification];

        $uf = $school->edcenso_uf_fk;

        $result = ['City' => '', 'Regional' => ''];
        $result['City'] = $this->actionGetCities($uf);

        $criteria = new CDbCriteria();
        $criteria->select = 't.*';
        $criteria->join = 'LEFT JOIN edcenso_city city ON city.id = t.edcenso_city_fk ';
        $criteria->condition = "city.edcenso_uf_fk = $uf";
        $criteria->order = 'name';
        $result['Regional'] = CHtml::tag('option', ['value' => ''], 'Selecione o órgão', true);
        $regional = CHtml::listData(EdcensoRegionalEducationOrgan::model()->findAll($criteria), 'code', 'name');
        foreach ($regional as $code => $name) {
            $result['Regional'] .= CHtml::tag('option', ['value' => $code], $name, true);
        }

        echo json_encode($result);
    }

    public function actionGetCities($uf = null)
    {
        if (isset($_POST[$this->schoolIdentification])) {
            $school = new SchoolIdentification();
            $school->attributes = $_POST[$this->schoolIdentification];
        }
        $data = EdcensoCity::model()->findAll('edcenso_uf_fk=:uf_id', [':uf_id' => (int)$school->edcenso_uf_fk]);
        $data = CHtml::listData($data, 'id', 'name');

        $result = CHtml::tag('option', ['value' => ''], 'Selecione a cidade', true);
        foreach ($data as $value => $name) {
            $result .= CHtml::tag('option', ['value' => $value], CHtml::encode($name), true);
        }

        return $result;
    }

    public function actionGetDistricts($city = null)
    {
        if (isset($_POST[$this->schoolIdentification])) {
            $school = new SchoolIdentification();
            $school->attributes = $_POST[$this->schoolIdentification];
        }
        $city = $city == null ? $school->edcenso_city_fk : $city;

        $data = EdcensoDistrict::model()->findAll('edcenso_city_fk=:city_id', [':city_id' => $city]);
        $data = CHtml::listData($data, 'code', 'name');

        $result = CHtml::tag('option', ['value' => ''], 'Selecione o distrito', true);

        foreach ($data as $value => $name) {
            $result .= CHtml::tag('option', ['value' => $value], CHtml::encode($name), true);
        }

        return $result;
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        $this->render(
            'view',
            [
                'modelSchoolIdentification' => $this->loadModel($id, $this->schoolIdentification),
                'modelSchoolStructure' => $this->loadModel($id, $this->schoolStucture),
            ]
        );
    }

    public function actionGetManagerCities()
    {
        $uf = $_POST['edcenso_uf_fk'];

        $data = EdcensoCity::model()->findAll('edcenso_uf_fk=:uf_id', [':uf_id' => $uf]);
        $data = CHtml::listData($data, 'id', 'name');

        $result = '';
        foreach ($data as $value => $name) {
            $result .= CHtml::tag('option', ['value' => $value], CHtml::encode($name), true);
        }

        echo $result;
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $modelSchoolIdentification = new SchoolIdentification();
        $modelSchoolStructure = new SchoolStructure();
        $modelManagerIdentification = new ManagerIdentification();

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($modelSchoolIdentification);

        if (isset($_POST[$this->schoolIdentification]) && isset($_POST[$this->schoolStucture]) && isset($_POST[$this->managerIdentification])) {
            if (isset($_POST[$this->schoolStucture]['shared_school_inep_id_1'])) {
                $sharedSchools = $_POST[$this->schoolStucture]['shared_school_inep_id_1'];
            }
            $_POST[$this->schoolStucture]['shared_school_inep_id_1'] = isset($sharedSchools[0]) ? $sharedSchools[0] : null;
            $_POST[$this->schoolStucture]['shared_school_inep_id_2'] = isset($sharedSchools[1]) ? $sharedSchools[1] : null;
            $_POST[$this->schoolStucture]['shared_school_inep_id_3'] = isset($sharedSchools[2]) ? $sharedSchools[2] : null;
            $_POST[$this->schoolStucture]['shared_school_inep_id_4'] = isset($sharedSchools[3]) ? $sharedSchools[3] : null;
            $_POST[$this->schoolStucture]['shared_school_inep_id_5'] = isset($sharedSchools[4]) ? $sharedSchools[4] : null;
            $_POST[$this->schoolStucture]['shared_school_inep_id_6'] = isset($sharedSchools[5]) ? $sharedSchools[5] : null;

            $modelSchoolIdentification->attributes = $_POST[$this->schoolIdentification];
            $modelSchoolStructure->attributes = $_POST[$this->schoolStucture];
            $modelManagerIdentification->attributes = $_POST[$this->managerIdentification];

            $modelManagerIdentification->cpf = str_replace(['.', '-'], '', $modelManagerIdentification->cpf);
            $modelManagerIdentification->filiation_1_cpf = str_replace(['.', '-'], '', $modelManagerIdentification->filiation_1_cpf);
            $modelManagerIdentification->filiation_2_cpf = str_replace(['.', '-'], '', $modelManagerIdentification->filiation_2_cpf);
            $modelManagerIdentification->school_inep_id_fk = $modelSchoolIdentification->inep_id;

            $modelSchoolStructure->school_inep_id_fk = $modelSchoolIdentification->inep_id;

            /*
             *
             *
             * tratar upload do brasao da escola aqui no create
             *
             *
             */

            if ($modelSchoolIdentification->validate() && $modelSchoolStructure->validate() && $modelManagerIdentification->validate()) {
                if ($modelSchoolStructure->operation_location_building || $modelSchoolStructure->operation_location_temple || $modelSchoolStructure->operation_location_businness_room || $modelSchoolStructure->operation_location_instructor_house || $modelSchoolStructure->operation_location_other_school_room || $modelSchoolStructure->operation_location_barracks || $modelSchoolStructure->operation_location_socioeducative_unity || $modelSchoolStructure->operation_location_prison_unity || $modelSchoolStructure->operation_location_other) {
                    if ($modelSchoolIdentification->save() && $modelSchoolStructure->save() && $modelManagerIdentification->save()) {
                        foreach ($_POST[$this->schoolStucture]['stages'] as $stage) {
                            $schoolStages = new SchoolStages();
                            $schoolStages->school_fk = $modelSchoolIdentification->inep_id;
                            $schoolStages->edcenso_stage_vs_modality_fk = $stage;
                            $schoolStages->save();
                        }
                        Log::model()->saveAction('school', $modelSchoolIdentification->inep_id, 'C', $modelSchoolIdentification->name);
                        Yii::app()->user->setFlash('success', Yii::t('default', 'Escola adicionada com sucesso!'));
                        $this->redirect(['index']);
                    }
                } else {
                    $modelSchoolStructure->addError('operation_location_building', Yii::t('default', 'Operation Location') . ' ' . Yii::t('default', 'cannot be blank'));
                }
            }
        }

        $this->render(
            'create',
            [
                'modelSchoolIdentification' => $modelSchoolIdentification,
                'modelSchoolStructure' => $modelSchoolStructure,
                'modelManagerIdentification' => $modelManagerIdentification
            ]
        );
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $modelSchoolIdentification = $this->loadModel($id, $this->schoolIdentification);
        $modelSchoolStructure = $this->loadModel($id, $this->schoolStucture);
        $modelManagerIdentification = $this->loadModel($id, $this->managerIdentification);

        $disableFieldWhenItsUBATUBA = Yii::app()->features->isEnable(TFeature::FEAT_INTEGRATIONS_SEDSP);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($modelSchoolIdentification);

        if (isset($_POST[$this->schoolIdentification]) && isset($_POST[$this->schoolStucture]) && isset($_POST[$this->managerIdentification])) {
            if (isset($_POST[$this->schoolStucture]['shared_school_inep_id_1'])) {
                $sharedSchools = $_POST[$this->schoolStucture]['shared_school_inep_id_1'];
            }
            $_POST[$this->schoolStucture]['shared_school_inep_id_1'] = isset($sharedSchools[0]) ? $sharedSchools[0] : null;
            $_POST[$this->schoolStucture]['shared_school_inep_id_2'] = isset($sharedSchools[1]) ? $sharedSchools[1] : null;
            $_POST[$this->schoolStucture]['shared_school_inep_id_3'] = isset($sharedSchools[2]) ? $sharedSchools[2] : null;
            $_POST[$this->schoolStucture]['shared_school_inep_id_4'] = isset($sharedSchools[3]) ? $sharedSchools[3] : null;
            $_POST[$this->schoolStucture]['shared_school_inep_id_5'] = isset($sharedSchools[4]) ? $sharedSchools[4] : null;
            $_POST[$this->schoolStucture]['shared_school_inep_id_6'] = isset($sharedSchools[5]) ? $sharedSchools[5] : null;

            $fileContentTmp = $modelSchoolIdentification->logo_file_content;

            $modelSchoolIdentification->attributes = $_POST[$this->schoolIdentification];
            $modelSchoolStructure->attributes = $_POST[$this->schoolStucture];
            $modelManagerIdentification->attributes = $_POST[$this->managerIdentification];

            $modelSchoolIdentification->number_ato = $_POST[$this->schoolIdentification]['number_ato'];

            if (!empty($_FILES['SchoolIdentification']['tmp_name']['logo_file_content'])) {
                $file = CUploadedFile::getInstance($modelSchoolIdentification, 'logo_file_content');
                $modelSchoolIdentification->logo_file_name = $file->name;
                $modelSchoolIdentification->logo_file_type = $file->type;
                $modelSchoolIdentification->logo_file_content = file_get_contents($file->tempName);
            } else {
                $modelSchoolIdentification->logo_file_content = $fileContentTmp;
            }

            $modelManagerIdentification->cpf = str_replace(['.', '-'], '', $modelManagerIdentification->cpf);
            $modelManagerIdentification->filiation_1_cpf = str_replace(['.', '-'], '', $modelManagerIdentification->filiation_1_cpf);
            $modelManagerIdentification->filiation_2_cpf = str_replace(['.', '-'], '', $modelManagerIdentification->filiation_2_cpf);
            $modelManagerIdentification->school_inep_id_fk = $modelSchoolIdentification->inep_id;

            $modelSchoolStructure->school_inep_id_fk = $modelSchoolIdentification->inep_id;

            if ($modelSchoolIdentification->validate() && $modelSchoolStructure->validate() && $modelManagerIdentification->validate()) {
                if (
                    $modelSchoolStructure->operation_location_building || $modelSchoolStructure->operation_location_other_school_room || $modelSchoolStructure->operation_location_barracks
                    || $modelSchoolStructure->operation_location_socioeducative_unity || $modelSchoolStructure->operation_location_prison_unity || $modelSchoolStructure->operation_location_other
                ) {
                    if ($modelSchoolIdentification->save() && $modelSchoolStructure->save() && $modelManagerIdentification->save()) {
                        $criteriaStages = new CDbCriteria();
                        $criteriaStages->condition = 'school_fk = :school_fk';
                        $criteriaStages->params = [
                            ':school_fk' => $modelSchoolIdentification->inep_id,
                        ];

                        if ($_POST[$this->schoolStucture]['stages'] != '') {
                            $criteriaStages->addNotInCondition('edcenso_stage_vs_modality_fk', $_POST[$this->schoolStucture]['stages']);
                            SchoolStages::model()->deleteAll($criteriaStages);

                            foreach ($_POST[$this->schoolStucture]['stages'] as $stage) {
                                $schoolStages = SchoolStages::model()->find('school_fk = :school_fk and edcenso_stage_vs_modality_fk = :edcenso_stage_vs_modality_fk', [':school_fk' => $modelSchoolIdentification->inep_id, ':edcenso_stage_vs_modality_fk' => $stage]);
                                if ($schoolStages == null) {
                                    $schoolStages = new SchoolStages();
                                    $schoolStages->school_fk = $modelSchoolIdentification->inep_id;
                                    $schoolStages->edcenso_stage_vs_modality_fk = $stage;
                                    $schoolStages->save();
                                }
                            }
                        } else {
                            SchoolStages::model()->deleteAll($criteriaStages);
                        }

                        Log::model()->saveAction('school', $modelSchoolIdentification->inep_id, 'U', $modelSchoolIdentification->name);
                        Yii::app()->user->setFlash('success', Yii::t('default', 'Escola alterada com sucesso!'));
                        $this->redirect(['index']);
                    }
                } else {
                    $modelSchoolStructure->addError('operation_location_building', Yii::t('default', 'Operation Location') . ' ' . Yii::t('default', 'cannot be blank'));
                }
            }
        }

        $this->render(
            'update',
            [
                'modelSchoolIdentification' => $modelSchoolIdentification,
                'modelSchoolStructure' => $modelSchoolStructure,
                'modelManagerIdentification' => $modelManagerIdentification,
                'disabledFields' => $disableFieldWhenItsUBATUBA
            ]
        );
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        if ($this->loadModel($id, $this->schoolStucture)->delete() && $this->loadModel($id, $this->schoolIdentification)->delete() && $this->loadModel($id, $this->managerIdentification)->delete()) {
            Yii::app()->user->setFlash('success', Yii::t('default', 'Escola excluída com sucesso!'));
            $this->redirect(['index']);
        } else {
            throw new CHttpException(404, 'A página requisitada não existe.');
        }
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $filter = new SchoolIdentification('search');
        $filter->unsetAttributes(); // clear any default values
        if (isset($_GET['SchoolIdentification'])) {
            $filter->attributes = $_GET['SchoolIdentification'];
        }
        $dataProvider = new CActiveDataProvider($this->schoolIdentification, ['pagination' => false]);
        $this->render(
            'index',
            [
                'dataProvider' => $dataProvider,
                'filter' => $filter
            ]
        );
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $modelSchoolIdentification = new SchoolIdentification('search');
        $modelSchoolIdentification->unsetAttributes(); // clear any default values
        $modelSchoolStructure = new SchoolStructure('search');
        $modelSchoolStructure->unsetAttributes(); // clear any default values

        if (isset($_GET[$this->schoolIdentification]) && isset($_GET[$this->schoolStucture])) {
            $modelSchoolIdentification->attributes = $_GET[$this->schoolIdentification];
            $modelSchoolStructure->attributes = $_GET[$this->schoolStucture];
        }

        $this->render(
            'admin',
            [
                'modelSchoolIdentification' => $modelSchoolIdentification,
                'modelSchoolStructure' => $modelSchoolStructure,
            ]
        );
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id, $model)
    {
        if ($model == $this->schoolIdentification) {
            return $this->loadSchoolIdentification($id);
        } elseif ($model == $this->schoolStucture) {
            return $this->loadSchoolStruct($id);
        } elseif ($model == $this->managerIdentification) {
            return $this->loadManagerIdentification($id);
        }
    }

    /**
     * Summary of loadSchoolIdentification
     * @param string $id
     *
     * @throws \CHttpException
     *
     * @return SchoolIdentification
     */
    private function loadSchoolIdentification($id)
    {
        $school = SchoolIdentification::model()->findByPk($id);

        if (!isset($school)) {
            throw new CHttpException(404, 'A escola requisitada não existe.');
        }

        return $school;
    }

    private function loadSchoolStruct($id)
    {
        $schoolStruct = SchoolStructure::model()->findByPk($id);
        if (!isset($schoolStruct)) {
            $schoolStruct = new SchoolStructure();
        }
        $stagesArray = [];
        $schoolStages = SchoolStages::model()->findAll('school_fk = :school_fk', ['school_fk' => $id]);
        foreach ($schoolStages as $stage) {
            array_push($stagesArray, $stage->edcenso_stage_vs_modality_fk);
        }
        $schoolStruct->stages = $stagesArray;
        $sharedSchoolInedIdArray = [];
        if ($schoolStruct->shared_school_inep_id_1 != null) {
            array_push($sharedSchoolInedIdArray, $schoolStruct->shared_school_inep_id_1);
        }
        if ($schoolStruct->shared_school_inep_id_2 != null) {
            array_push($sharedSchoolInedIdArray, $schoolStruct->shared_school_inep_id_2);
        }
        if ($schoolStruct->shared_school_inep_id_3 != null) {
            array_push($sharedSchoolInedIdArray, $schoolStruct->shared_school_inep_id_1);
        }
        if ($schoolStruct->shared_school_inep_id_4 != null) {
            array_push($sharedSchoolInedIdArray, $schoolStruct->shared_school_inep_id_4);
        }
        if ($schoolStruct->shared_school_inep_id_5 != null) {
            array_push($sharedSchoolInedIdArray, $schoolStruct->shared_school_inep_id_5);
        }
        if ($schoolStruct->shared_school_inep_id_6 != null) {
            array_push($sharedSchoolInedIdArray, $schoolStruct->shared_school_inep_id_6);
        }
        $schoolStruct->shared_school_inep_id_1 = $sharedSchoolInedIdArray;

        return $schoolStruct;
    }

    private function loadManagerIdentification($id)
    {
        $manager = ManagerIdentification::model()->findByAttributes(['school_inep_id_fk' => $id]);

        if ($manager) {
            return $manager;
        }

        return new ManagerIdentification();
    }

    public function actionDisplayLogo($id)
    {
        $model = $this->loadModel($id, $this->schoolIdentification);
        header('Content-Type: ' . $model->logo_file_type);
        if ($model->logo_file_content != null) {
            print $model->logo_file_content;
            return;
        }

        $baseUrl = Yii::app()->getBaseUrl(true);
        $themeUrl = Yii::app()->theme->baseUrl;
        $schoolLogo = $baseUrl . $themeUrl . '/img/emblema-escola.svg';
        header('Content-Type: image/svg+xml');
        print file_get_contents($schoolLogo);
    }

    public function actionRemoveLogo($id)
    {
        $model = $this->loadModel($id, $this->schoolIdentification);
        $model->logo_file_name = null;
        $model->logo_file_type = null;
        $model->logo_file_content = null;
        $model->save();
        echo '<script>window.history.back();</script>';
    }

    public function actionReports($id)
    {
        $this->layout = 'reports';
        $model = $this->loadModel($id, $this->schoolIdentification);
        $this->render(
            'MonthlySummary',
            [
                'model' => $model
            ]
        );
    }

    public function actionReportsMonthlyTransaction($id, $type)
    {
        $this->layout = 'reports';
        $model = $this->loadModel($id, $this->schoolIdentification);
        $title = '';

        switch ($type) {
            case 1:
                $title = 'ENSINO FUNDAMENTAL - ANOS INICIAIS';
                break;

            case 2:
                $title = 'ENSINO FUNDAMENTAL - ANOS FINAIS';
                break;
            case 3:
                $title = 'EDUCAÇÃO INFANTIL - PRÉ-ESCOLA';
                break;
            default:
                break;
        }

        $this->render(
            'MonthlyTransaction',
            [
                'model' => $model,
                'type' => $type,
                'title' => $title
            ]
        );
    }

    public function actionRecord($id, $type)
    {
        $this->layout = 'reports';
        $model = $this->loadModel($id, $this->schoolIdentification);
        $title = '';

        switch ($type) {
            case 1:
                $title = 'Hitórico Ensino Regular';
                break;

            case 2:
                $title = 'Histórico Ensino EJA';
                break;
            default:
                break;
        }

        $this->render(
            'Record',
            [
                'model' => $model,
                'type' => $type,
                'title' => $title
            ]
        );
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'school') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
