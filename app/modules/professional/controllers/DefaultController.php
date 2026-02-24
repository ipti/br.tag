<?php

class DefaultController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return [
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
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
            ['allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => ['index', 'create', 'update', 'delete', 'deleteAttendance', 'saveAllocation', 'deleteAllocation', 'viewAllocation'],
                'users' => ['@'],
            ],
            ['deny',  // deny all users
                'users' => ['*'],
            ],
        ];
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $modelProfessional = new Professional();

        if (isset($_POST['Professional'])) {
            $modelProfessional->attributes = $_POST['Professional'];
            $modelProfessional->inep_id_fk = Yii::app()->user->school;

            $professional = Professional::model()->findByAttributes([
                'inep_id_fk' => Yii::app()->user->school,
                'cpf_professional' => [$modelProfessional->cpf_professional, str_replace(['.', '-'], '', $modelProfessional->cpf_professional)]
            ]);

            if ($professional === null) {
                $modelProfessional->cpf_professional = str_replace(['.', '-'], '', $modelProfessional->cpf_professional);
                if ($modelProfessional->validate()) {
                    if ($modelProfessional->save()) {
                        Yii::app()->user->setFlash('success', Yii::t('default', 'Profissional cadastrado com sucesso!'));
                        $this->redirect(['index']);
                    }
                }
            } else {
                Yii::app()->user->setFlash('error', Yii::t('default', 'Profissional já cadastrado para esta escola!'));
                $this->redirect(['index']);
            }
        }

        $this->render('create', [
            'modelProfessional' => $modelProfessional,
        ]);
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $criteria = new CDbCriteria();
        $criteria->condition = 'professional_fk = ' . $id;
        $criteria->addCondition("YEAR(date) = " . Yii::app()->user->year);
        $criteria->order = 'date desc';

        $modelProfessional = Professional::model()->findByPk($id);
        $modelAttendance = new Attendance();
        
        $attendanceProvider = new CActiveDataProvider('Attendance', [
            'criteria' => $criteria,
            'pagination' => false,
        ]);

        $allocationCriteria = new CDbCriteria();
        $allocationCriteria->condition = 'professional_fk = ' . $id;
        $allocationCriteria->addCondition("school_year = " . Yii::app()->user->year);
        $allocationCriteria->order = 'id DESC';

        $allocationProvider = new CActiveDataProvider('ProfessionalAllocation', [
            'criteria' => $allocationCriteria,
            'pagination' => false,
        ]);

        if (isset($_POST['Attendance'])) {
            $modelAttendance->attributes = $_POST['Attendance'];
            $modelAttendance->professional_fk = $modelProfessional->id_professional;

            $modelAttendance->date = Yii::app()->dateFormatter->format(
                'yyyy-MM-dd',
                CDateTimeParser::parse($modelAttendance->date, 'dd/MM/yyyy')
            );

            if ($modelAttendance->validate()) {
                if ($modelAttendance->save()) {
                    Yii::app()->user->setFlash('success', Yii::t('default', 'Atendimento adicionado com sucesso!'));
                    $this->redirect(['update', 'id' => $id]);
                }
            }
        }

        if (isset($_POST['Professional'])) {
            $modelProfessional->attributes = $_POST['Professional'];

            if ($modelProfessional->save()) {
                Yii::app()->user->setFlash('success', Yii::t('default', 'Profissional atualizado com sucesso!'));
                $this->redirect(['index']);
            } else {
                Yii::app()->user->setFlash('error', Yii::t('default', 'Não foi possível atualizar o profissional!'));
                $this->redirect(['index']);
            }
        }

        $currentMonth = date('n');
        $currentYear  = date('Y');

        $totalAttendancesMonth = Yii::app()->db->createCommand(
            'SELECT COUNT(*) FROM attendance
              WHERE professional_fk = :pid
                AND MONTH(date) = :month
                AND YEAR(date)  = :year'
        )->bindValues([
            ':pid'   => $id,
            ':month' => $currentMonth,
            ':year'  => $currentYear,
        ])->queryScalar();

        $totalAllocations = Yii::app()->db->createCommand(
            'SELECT COUNT(*) FROM professional_allocation
              WHERE professional_fk = :pid
                AND school_year = :year'
        )->bindValues([
            ':pid'  => $id,
            ':year' => Yii::app()->user->year,
        ])->queryScalar();

        $allocationModel = new ProfessionalAllocation();
        $allocationModel->professional_fk = $id;
        $allocationModel->school_year     = Yii::app()->user->year;

        $schools = CHtml::listData(
            SchoolIdentification::model()->findAll(['order' => 'name']),
            'inep_id',
            'name'
        );

        $this->render('update', [
            'modelProfessional'      => $modelProfessional,
            'attendanceProvider'     => $attendanceProvider,
            'allocationProvider'     => $allocationProvider,
            'modelAttendance'        => $modelAttendance,
            'totalAttendancesMonth'  => $totalAttendancesMonth,
            'totalAllocations'       => $totalAllocations,
            'allocationModel'        => $allocationModel,
            'schools'                => $schools,
        ]);
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        $professional = Professional::model()->findByPk($id);
        $attendance = Attendance::model()->findAllByAttributes(['professional_fk' => $id]);

        foreach ($attendance as $att) {
            $att->delete();
        }

        if ($professional->delete()) {
            Yii::app()->user->setFlash('success', Yii::t('default', 'Profissional excluído com sucesso!'));
            $this->redirect(['index']);
        }
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $criteria = new CDbCriteria();
        $criteria->with = ['allocations'];
        $criteria->together = true;
        
        $criteria->compare('allocations.school_inep_fk', Yii::app()->user->school);
        $criteria->compare('allocations.school_year', Yii::app()->user->year);
        $criteria->compare('allocations.location_type', 'school');
        
        $criteria->order = 't.name ASC';
        $criteria->group = 't.id_professional';

        $dataProvider = new CActiveDataProvider('Professional', [
            'criteria' => $criteria,
            'pagination' => [
                'pageSize' => 50,
            ]
        ]);

        $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionDeleteAttendance()
    {
        $attendanceId = Yii::app()->request->getPost('attendance');
        $model = Attendance::model()->findByPk($attendanceId);
        $model->delete();
        header('HTTP/1.1 200 OK');
    }

    public function actionSaveAllocation()
    {
        header('Content-Type: application/json');

        if (!TagUtils::isAdmin()) {
            echo json_encode(['success' => false, 'message' => 'Acesso negado']);
            Yii::app()->end();
        }

        if (isset($_POST['ProfessionalAllocation'])) {
            $model = new ProfessionalAllocation();

            if (isset($_POST['ProfessionalAllocation']['id']) && !empty($_POST['ProfessionalAllocation']['id'])) {
                $model = ProfessionalAllocation::model()->findByPk($_POST['ProfessionalAllocation']['id']);
                if (!$model) {
                    echo json_encode(['success' => false, 'message' => 'Lotação não encontrada']);
                    Yii::app()->end();
                }
            }

            $model->attributes = $_POST['ProfessionalAllocation'];

            // Set scenario based on location type
            if ($model->location_type === 'school') {
                $model->scenario = 'school_location';
                $model->location_name = null; // Clear location_name when school is selected
            } else {
                $model->scenario = 'other_location';
                $model->school_inep_fk = null; // Clear school when secretariat/other is selected
            }

            if ($model->save()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'errors' => $model->errors]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Dados não recebidos']);
        }

        Yii::app()->end();
    }

    public function actionDeleteAllocation()
    {
        header('Content-Type: application/json');
        if (!TagUtils::isAdmin()) {
            echo CJSON::encode(['success' => false, 'errors' => ['access' => 'Você não tem permissão para realizar esta ação.']]);
            Yii::app()->end();
        }

        $id = Yii::app()->request->getPost('id');
        $model = ProfessionalAllocation::model()->findByPk($id);
        if ($model && $model->delete()) {
            echo CJSON::encode(['success' => true]);
            Yii::app()->end();
        } else {
            echo CJSON::encode(['success' => false]);
            Yii::app()->end();
        }
    }

    public function actionViewAllocation($id)
    {
        header('Content-Type: application/json');
        
        if (!TagUtils::isAdmin()) {
            echo CJSON::encode(['success' => false, 'message' => 'Acesso negado']);
            Yii::app()->end();
        }

        $model = ProfessionalAllocation::model()->findByPk($id);
        
        if ($model) {
            echo CJSON::encode([
                'success' => true,
                'data' => $model->attributes
            ]);
        } else {
            echo CJSON::encode(['success' => false, 'message' => 'Lotação não encontrada']);
            Yii::app()->end();
        }
    }
    /**
     * Helper method to generate allocation grid actions
     * @param ProfessionalAllocation $data
     * @return string HTML
     */
    public function getAllocationActions($data)
    {
        if (!TagUtils::isAdmin()) {
            return "";
        }

        $editBtn = CHtml::link(
            CHtml::image(Yii::app()->theme->baseUrl . "/img/editar.svg", "Editar", ["style" => "width: 16px; margin-right: 10px;"]),
            "javascript:void(0)",
            [
                "class" => "btn-edit-allocation", 
                "title" => "Editar",
                "data-allocation" => CJSON::encode($data->attributes)
            ]
        );

        $deleteBtn = CHtml::link(
            CHtml::image(Yii::app()->theme->baseUrl . "/img/deletar.svg", "Excluir", ["style" => "width: 16px;"]),
            "javascript:void(0)",
            [
                "class" => "btn-delete-allocation",
                "title" => "Excluir",
                "data-id" => $data->id
            ]
        );

        return $editBtn . $deleteBtn;
    }
}
