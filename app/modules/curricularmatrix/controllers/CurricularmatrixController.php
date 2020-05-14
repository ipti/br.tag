<?php

	class CurricularmatrixController extends Controller {

		public $MODEL_CURRICULAR_MATRINX = 'CurricularMatrix';

		/**
		 * @return array action filters
		 */
		public function filters() {
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
		public function accessRules() {
			return [
				[
					'allow',  // allow all users to perform 'index' and 'view' actions
					'actions' => [], 'users' => ['*'],
				], [
					'allow', // allow authenticated user to perform 'create' and 'update' actions
					'actions' => ['index', 'addMatrix'], 'users' => ['@'],
				], [
					'allow', // allow admin user to perform 'admin' and 'delete' actions
					'actions' => [], 'users' => ['admin'],
				], [
					'deny',  // deny all users
					'users' => ['*'],
				],
			];
		}


		public function actionIndex() {


			$this->render('index', $this->getDataProviderAndFilter());
		}

		public function actionAddMatrix() {
			$stages = $_POST['stages'];
			$disciplines = $_POST['disciplines'];
			$workload = $_POST['workload'];
			$credits = $_POST['credits'];
			$school = Yii::app()->user->school;

			if (isset($stages, $disciplines, $workload, $credits)) {
				foreach ($stages as $stage) {
					foreach ($disciplines as $discipline) {
						$matrix = CurricularMatrix::model()->find("stage_fk = :stage and discipline_fk = :discipline and school_fk = :school", [
							":stage" => $stage, ":discipline" => $discipline, ":school" => $school
						]);
						$logSituation = "U";
						if ($matrix == NULL) {
							$matrix = new CurricularMatrix();
							$matrix->setAttributes([
								"stage_fk" => $stage, "school_fk" => $school, "discipline_fk" => $discipline
							]);
							$logSituation = "C";
						}
						$matrix->setAttributes([
							"workload" => $workload, "credits" => $credits,
						]);
						$stageName = EdcensoStageVsModality::model()->find("id = :stage", [":stage" => $stage])->name;
						$disciplineName = EdcensoDiscipline::model()->find("id = :discipline", [":discipline" => $discipline])->name;
						Log::model()->saveAction("curricular_matrix", $stage . "|" . $discipline, $logSituation, $stageName . "|". $disciplineName);
						$matrix->save();
					}

				}
			}
		}


		private function getDataProviderAndFilter() {

			$filter = new CurricularMatrix('search');
			$filter->unsetAttributes();
			$filter->school_fk = Yii::app()->user->school;

			$dataProvider =
				new CActiveDataProvider('CurricularMatrix', [
				'pagination' => [
					'pageSize' => 20,
				]
			]);

			return ['dataProvider'=>$dataProvider, 'filter'=>$filter];

		}


		public function loadModel($id, $model) {
			$return = null;

			if ($model == $this->MODEL_CURRICULAR_MATRINX) {
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
    public function actionDelete($id) {
			$curricularMatrix = $this->loadModel($id, $this->MODEL_CURRICULAR_MATRINX);
			try {
					if ($curricularMatrix->delete()) {
							Yii::app()->user->setFlash('success', Yii::t('default', 'Matriz Curricular excluída com sucesso!'));
							$this->redirect(array('index'));
					}
			} catch(Exception $e) {
					throw new CHttpException(901, Yii::t('errors','Can not delete'));
			}
	}

	}