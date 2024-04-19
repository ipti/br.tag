<?php

class CurricularmatrixController extends Controller
{

    public $modelCurricularMatrix = 'CurricularMatrix';

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

        $errorMsg = "Preencha os campos de etapa, Componentes curriculares/eixos, carga horária e horas semanais.";

        if ($stages === "" ||
            $disciplines === "" ||
            $workload === "" ||
            $credits === "") {
            echo json_encode([
                "valid" => false,
                "message" => $errorMsg
            ]);
            return;
        }


        define('YEAR_PARAM', ':year');
        foreach ($stages as $stage) {
            foreach ($disciplines as $discipline) {
                $matrix = CurricularMatrix::model()->find(
                    "stage_fk = :stage and discipline_fk = :discipline and school_year = " . YEAR_PARAM,
                    [
                        ":stage" => $stage, ":discipline" => $discipline, YEAR_PARAM => Yii::app()->user->year
                    ]
                );
                $logSituation = "U";
                if ($matrix == null) {
                    $matrix = new CurricularMatrix();
                    $matrix->setAttributes(
                        [
                          "stage_fk" => $stage, "discipline_fk" => $discipline, "school_year" => Yii::app()->user->year
                        ]
                    );
                    $logSituation = "C";
                }
                $matrix->setAttributes([
                    "workload" => $workload, "credits" => $credits,
                ]);

                $stageName = EdcensoStageVsModality::model()->find("id = :stage", [":stage" => $stage])->name;
                $disciplineModel = EdcensoDiscipline::model()->find("id = :discipline", [":discipline" => $discipline]);

                $disciplineName = $disciplineModel->name;
                $result = $matrix->save();


                if ($result) {
                    // Armazena os argumentos da função saveAction em variáveis separadas
                    $logSubject = $stage . "|" . $discipline;
                    $logDetail = $stageName . "|" . $disciplineName;

                    // Chama a função saveAction com os argumentos separados
                    Log::model()->saveAction("curricular_matrix", $logSubject, $logSituation, $logDetail);
                }

            }
        }
        echo json_encode(["valid" => true, "message" => "Matriz inserida com sucesso!"]);
    }


    public function actionMatrixReuse()
    {
        $previousYear = Yii::app()->user->year - 1;

        $curricularMatrixesPreviousYear = CurricularMatrix::model()->findAll(
            "school_year = :year",
            [":year" => $previousYear]
        );
        foreach ($curricularMatrixesPreviousYear as $curricularMatrixPreviousYear) {
            $query = "stage_fk = :stage_fk
            and discipline_fk = :discipline_fk
            and school_year = :year";
            $params = [
            ":stage_fk" => $curricularMatrixPreviousYear->stage_fk,
            ":discipline_fk" => $curricularMatrixPreviousYear->discipline_fk,
            ":year" => Yii::app()->user->year
            ];
            $curricularMatrixCurrentYear = CurricularMatrix::model()->find($query, $params);

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
        echo json_encode(["valid" => true]);
    }


    private function getDataProviderAndFilter()
    {
        $dataProvider = CurricularMatrix::model()->search();

        return ['dataProvider' => $dataProvider];

    }


    public function loadModel($id, $model)
    {
        $return = null;

        if ($model == $this->modelCurricularMatrix) {
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
        // Definindo a constante para a URL
        define('CURRICULAR_MATRIX_URL', '?r=curricularmatrix');

        $curricularMatrix = $this->loadModel($id, $this->modelCurricularMatrix);
        $this->getScheduleCount($curricularMatrix->discipline_fk, $curricularMatrix->stage_fk);
        $teachingDatas = $this->getTeachingMatrixCount($id);

        if ((int)$teachingDatas["qtd"] !== 0) {
            Yii::app()->user->setFlash(
                'error',
                Yii::t(
                    'default',
                    'Não se pode remover uma matriz que está esteja vinculada a algum professor de alguma turma.'
                )
            );
            $this->redirect(CURRICULAR_MATRIX_URL);
            return;
        }

        try {
            if ($curricularMatrix->delete()) {
                Yii::app()->user->setFlash('success', Yii::t('default', 'Matriz excluída com sucesso!'));
                $this->redirect(CURRICULAR_MATRIX_URL);
            }
        } catch (Exception $e) {
            Yii::app()->user->setFlash(
                'error',
                Yii::t(
                    'default',
                    'Um erro aconteceu. Não foi possível remover a matriz curricular.'
                )
            );

            $this->redirect(CURRICULAR_MATRIX_URL);
        }
    }


    private function getScheduleCount($disciplineFk, $stageFk)
    {
        return Yii::app()->db->createCommand("
            SELECT COUNT(s.id) AS qtd
            FROM schedule s
            JOIN classroom c ON s.classroom_fk = c.id
            WHERE s.discipline_fk = :discipline_fk AND c.edcenso_stage_vs_modality_fk = :stage_fk")
            ->bindParam(":discipline_fk", $disciplineFk)
            ->bindParam(":stage_fk", $stageFk)
            ->queryRow();
    }

    private function getTeachingMatrixCount($id)
    {
        return Yii::app()->db->createCommand("
            SELECT COUNT(tm.id) AS qtd
            FROM teaching_matrixes tm
            WHERE curricular_matrix_fk = :id")
            ->bindParam(":id", $id)
            ->queryRow();
    }
}

