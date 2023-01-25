<?php

class CurricularmatrixController extends Controller
{
    public $MODEL_CURRICULAR_MATRIX = 'CurricularMatrix';

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
            [
                'allow',  // allow all users to perform 'index' and 'view' actions
                'actions' => [], 'users' => ['*'],
            ], [
                'allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => ['index', 'addMatrix', 'matrixReuse'], 'users' => ['@'],
            ], [
                'allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => [], 'users' => ['admin'],
            ], [
                'deny',  // deny all users
                'users' => ['*'],
            ],
        ];
    }

    public function actionIndex()
    {
        $this->render('index', $this->getDataProviderAndFilter());
    }

    public function actionAddMatrix()
    {
        $stages = $_POST['stages'];
        $disciplines = $_POST['disciplines'];
        $workload = $_POST['workload'];
        $credits = $_POST['credits'];
        if ($stages !== '' && $disciplines !== '' && $workload !== '' && $credits !== '') {
            foreach ($stages as $stage) {
                foreach ($disciplines as $discipline) {
                    $matrix = CurricularMatrix::model()->find('stage_fk = :stage and discipline_fk = :discipline and school_year = :year', [
                        ':stage' => $stage, ':discipline' => $discipline, ':year' => Yii::app()->user->year
                    ]);
                    $logSituation = 'U';
                    if ($matrix == null) {
                        $matrix = new CurricularMatrix();
                        $matrix->setAttributes([
                            'stage_fk' => $stage, 'discipline_fk' => $discipline, 'school_year' => Yii::app()->user->year
                        ]);
                        $logSituation = 'C';
                    }
                    $matrix->setAttributes([
                        'workload' => $workload, 'credits' => $credits,
                    ]);
                    $stageName = EdcensoStageVsModality::model()->find('id = :stage', [':stage' => $stage])->name;
                    $disciplineName = EdcensoDiscipline::model()->find('id = :discipline', [':discipline' => $discipline])->name;
                    Log::model()->saveAction('curricular_matrix', $stage . '|' . $discipline, $logSituation, $stageName . '|' . $disciplineName);
                    $matrix->save();
                }
            }
            echo json_encode(['valid' => true, 'message' => 'Matriz inserida com sucesso!']);
        } else {
            echo json_encode(['valid' => false, 'message' => 'Preencha os campos de etapa, disciplinas, carga horária e horas semanais.']);
        }
    }

    public function actionMatrixReuse()
    {
        $curricularMatrixesPreviousYear = CurricularMatrix::model()->findAll('school_year = :year', [':year' => Yii::app()->user->year - 1]);
        foreach ($curricularMatrixesPreviousYear as $curricularMatrixPreviousYear) {
            $curricularMatrixCurrentYear = CurricularMatrix::model()->find('stage_fk = :stage_fk and discipline_fk = :discipline_fk and school_year = :year', [':stage_fk' => $curricularMatrixPreviousYear->stage_fk, ':discipline_fk' => $curricularMatrixPreviousYear->discipline_fk, ':year' => Yii::app()->user->year]);
            if ($curricularMatrixCurrentYear == null) {
                $curricularMatrixCurrentYear = new CurricularMatrix();
                $curricularMatrixCurrentYear->stage_fk = $curricularMatrixPreviousYear->stage_fk;
                $curricularMatrixCurrentYear->discipline_fk = $curricularMatrixPreviousYear->discipline_fk;
            }
            $curricularMatrixCurrentYear->workload = $curricularMatrixPreviousYear->workload;
            $curricularMatrixCurrentYear->credits = $curricularMatrixPreviousYear->credits;
            $curricularMatrixCurrentYear->school_year = Yii::app()->user->year;
            $curricularMatrixCurrentYear->save();
        }
        echo json_encode(['valid' => true]);
    }

    private function getDataProviderAndFilter()
    {
        $filter = new CurricularMatrix('search');
        $filter->unsetAttributes();
        if (isset($_GET['CurricularMatrix'])) {
            $filter->attributes = $_GET['CurricularMatrix'];
        }

        $dataProvider =
            new CActiveDataProvider('CurricularMatrix', [
                'pagination' => [
                    'pageSize' => 20,
                ]
            ]);

        return ['dataProvider' => $dataProvider, 'filter' => $filter];
    }

    public function loadModel($id, $model)
    {
        $return = null;

        if ($model == $this->MODEL_CURRICULAR_MATRIX) {
            $return = CurricularMatrix::model()->findByPk($id);
        }

        if ($return === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $return;
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    //@done s1 - excluir Matrix Curricular
    public function actionDelete($id)
    {
        $curricularMatrix = $this->loadModel($id, $this->MODEL_CURRICULAR_MATRIX);
        $schedules = Yii::app()->db->createCommand('
            select count(s.id) as qtd from schedule s 
            join classroom c on s.classroom_fk = c.id 
            where s.discipline_fk = ' . $curricularMatrix->discipline_fk . ' and c.edcenso_stage_vs_modality_fk = ' . $curricularMatrix->stage_fk)->queryRow();
        $teachingDatas = Yii::app()->db->createCommand('
            select count(tm.id) as qtd from teaching_matrixes tm 
            where curricular_matrix_fk = :id')->bindParam(':id', $id)->queryRow();
        if ((int)$schedules['qtd'] === 0 && (int)$teachingDatas['qtd'] === 0) {
            try {
                if ($curricularMatrix->delete()) {
                    echo json_encode(['valid' => true, 'message' => 'Matriz excluída com sucesso!']);
                }
            } catch (Exception $e) {
                echo json_encode(['valid' => false, 'message' => 'Um erro aconteceu. Não foi possível remover a matriz curricular.']);
            }
        } else {
            if ((int)$schedules['qtd'] !== 0) {
                echo json_encode(['valid' => false, 'message' => 'Não se pode remover uma matriz que está sendo utilizada no quadro de horário de alguma turma.']);
            } else {
                echo json_encode(['valid' => false, 'message' => 'Não se pode remover uma matriz que está esteja vinculada a algum professor de alguma turma.']);
            }
        }
    }
}
