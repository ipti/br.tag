<?php

	class SiteController extends Controller {

		//@done S2 -FAzer Cadastro de usuário
		//@done s1 -Limitar a escolha de escolas apenas para o Administrador

		/**
		 * Declares class-based actions.
		 */
		public $layout = 'fullmenu';

		public function accessRules() {
			return [
				[
					'allow', // allow authenticated user to perform 'create' and 'update' actions
					'actions' => ['changeschool', 'loadMoreLogs'], 'users' => ['@'],
				],
			];
		}

		public function actions() {
			return [
				// captcha action renders the CAPTCHA image displayed on the contact page
				'captcha' => [
					'class' => 'CCaptchaAction', 'backColor' => 0xFFFFFF,
				], // page action renders "static" pages stored under 'protected/views/site/pages'
				// They can be accessed via: index.php?r=site/page&view=FileName
				'page' => [
					'class' => 'CViewAction',
				],
			];
		}

		/**
		 * This is the default 'index' action that is invoked
		 * when an action is not explicitly requested by users.
		 */
		public function actionIndex() {

			// renders the view file 'protected/views/site/index.php'
			// using the default layout 'protected/views/layouts/main.php'
			if (Yii::app()->user->isGuest) {
				$this->redirect(yii::app()->createUrl('site/login'));
			}
//        $this->redirect(yii::app()->createUrl('student'));
			$this->loadLogsHtml(5);
			$this->render('index', ["html" => $this->loadLogsHtml(5)]);
		}

		/**
		 * This is the action to handle external exceptions.
		 */
		public function actionError() {
			if ($error = Yii::app()->errorHandler->error) {
				if (Yii::app()->request->isAjaxRequest) {
					echo $error['message'];
				} else {
					$this->render('error', $error);
				}
			}
		}

		/**
		 * Displays the contact page
		 */
		public function actionContact() {
			$model = new ContactForm;
			if (isset($_POST['ContactForm'])) {
				$model->attributes = $_POST['ContactForm'];
				if ($model->validate()) {
					$name = '=?UTF-8?B?' . base64_encode($model->name) . '?=';
					$subject = '=?UTF-8?B?' . base64_encode($model->subject) . '?=';
					$headers = "From: $name <{$model->email}>\r\n" . "Reply-To: {$model->email}\r\n" . "MIME-Version: 1.0\r\n" . "Content-type: text/plain; charset=UTF-8";

					mail(Yii::app()->params['adminEmail'], $subject, $model->body, $headers);
					Yii::app()->user->setFlash('contact', 'Thank you for contacting us. We will respond to you as soon as possible.');
					$this->refresh();
				}
			}
			$this->render('contact', ['model' => $model]);
		}

		/**
		 * Displays the login page
		 */
		public function actionLogin() {
			$this->layout = "login";

			$model = new LoginForm;

			// if it is ajax validation request
			if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
				echo CActiveForm::validate($model);
				Yii::app()->end();
			}

			// collect user input data
			if (isset($_POST['LoginForm'])) {
				$model->attributes = $_POST['LoginForm'];
				// validate user input and redirect to the previous page if valid
				if ($model->validate() && $model->login()) {
					$this->layout = 'fullmenu';
					$this->redirect(Yii::app()->user->returnUrl);
				}
			}
			// display the login form
			$this->render('login', ['model' => $model]);
		}

		/**
		 * Logs out the current user and redirect to homepage.
		 */
		public function actionLogout() {
			Yii::app()->user->logout();
			$this->redirect(Yii::app()->homeUrl);
		}

		public function actionChangeSchool() {
			if (isset($_POST['UsersSchool']['school_fk']) && !empty($_POST['UsersSchool']['school_fk'])) {
				Yii::app()->user->school = $_POST['UsersSchool']['school_fk'];
			} else if (isset($_POST['SchoolIdentification']['inep_id']) && !empty($_POST['SchoolIdentification']['inep_id'])) {
				Yii::app()->user->school = $_POST['SchoolIdentification']['inep_id'];
			}

			$this->redirect(Yii::app()->homeUrl);
		}

		private function loadLogsHtml($limit, $date = NULL) {
			if ($date == NULL) {
				$logs = Log::model()->findAll("school_fk = :school order by date desc limit " . $limit, [':school' => Yii::app()->user->school]);
			} else {
				$logs = Log::model()->findAll("school_fk = :school and date < STR_TO_DATE(:date,'%d/%m/%Y à\s %H:%i:%s') order by date desc limit " . $limit, [':school' => Yii::app()->user->school, ':date' => $date]);
			}

			$html = "";
			if (count($logs) > 0) {
				foreach ($logs as $log) {
					$text = $icon = $color = $crud = "";

					switch ($log->crud) {
						case "C" :
							$crud = "criado(a)";
							$color = "lightgreen";
							break;
						case "U" :
							$crud = "atualizado(a)";
							$color = "lightskyblue";
							break;
						case "D":
							$crud = "excluído(a)";
							$color = "lightcoral";
							break;
					}

					switch ($log->reference) {
						case "class":
							$infos = explode("|", $log->additional_info);
							$text = 'As aulas ministradas da turma "' . $infos[0] . '" de ' . $infos[1] . ' do mês de ' . strtolower($infos[2]) . ' foram atualizadas.';
							$icon = "notes_2";
							break;
						case "frequency":
							$infos = explode("|", $log->additional_info);
							$text = 'A frequência da turma "' . $infos[0] . '" de ' . $infos[1] . ' do mês de ' . strtolower($infos[2]) . ' foi atualizada.';
							$icon = "check";
							break;
						case "classroom": //done
							$text = 'Turma "' . $log->additional_info . '" foi ' . $crud . ".";
							$icon = "adress_book";
							break;
						case "courseplan": //done
							$text = 'Plano de aula "' . $log->additional_info . '" foi ' . $crud . ".";
							$icon = "book_open";
							break;
						case "enrollment": //done
							$infos = explode("|", $log->additional_info);
							$text = '"' . $infos[0] . '" foi ' . $crud . ' na turma "' . $infos[1] . '".';
							$icon = "book";
							break;
						case "instructor": //done
							$text = 'Professor(a) "' . $log->additional_info . '" foi ' . $crud . ".";
							$icon = "nameplate";
							break;
						case "school": //done
							$text = 'Escola "' . $log->additional_info . '" foi ' . $crud . ".";
							$icon = "building";
							break;
						case "student": //done
							$text = 'Aluno(a) "' . $log->additional_info . '" foi ' . $crud . ".";
							$icon = "parents";
							break;
						case "grade": //done
							$text = 'As notas da turma "' . $log->additional_info . '" foram ' . $crud . ".";
							$icon = "list";
							break;
						case "calendar": //done
							$text = 'Calendário de ' . $log->additional_info . ' foi ' . $crud . ".";
							$icon = "calendar";
							break;
						case "curricular_matrix": //done
							$infos = explode("|", $log->additional_info);
							$text = 'Matriz curricular da disciplina "' . $infos[1] . '" da etapa "' . $infos[0] . '" foi ' . $crud . ".";
							$icon = "stats";
							break;
						case "lunch_stock": //done
							$infos = explode("|", $log->additional_info);
							if ($log->crud == "C") {
								$text = $infos[1] . ' de ' . $infos[0] . ' foram adicionados ao estoque.';
								$icon = "upload";
							} else {
								$text = $infos[1] . ' de ' . $infos[0] . ' foram removidos do estoque.';
								$icon = "download";
							}
							break;
						case "lunch_menu": //done
							$text = 'Cardápio "' . $log->additional_info . '" foi ' . $crud . ".";
							$icon = "notes";
							break;
						case "lunch_meal": //done
							$text = 'Uma refeição foi ' . $crud . ' no cardápio "' . $log->additional_info . '".';
							$icon = "cutlery";
							break;
						case "timesheet": //done
							$text = 'Quadro de Horário da turma "' . $log->additional_info . '" foi gerado.';
							$icon = "signal";
							break;
						case "wizard_classroom": //done
							$text = 'Turmas de ' . $log->additional_info . ' foram reaproveitadas.';
							$icon = "adress_book";
							break;
						case "wizard_student": //done
							$text = 'Alunos de ' . $log->additional_info . ' foram rematriculados.';
							$icon = "parents";
							break;
					}
					$date = date("d/m/Y à\s H:i:s", strtotime($log->date));
					$html .= '<li class="log" title=\'' . $text . '\'>'
						. '<span class="glyphicons ' . $icon . ' ' . $color . '"><i></i>' . $text . '</span>'
						. '<span class="log-date">' . $date . '</span>'
						. '<span class="log-author">' . $log->userFk->name . '- </span>'
						. '</li>';
				}
			} else {
				$html = '<li class="log">' . '<span class="glyphicons notes"><i></i> Não há atividades recentes.</span>' . '</li>';
			}

			return $html;
		}

		public function actionLoadMoreLogs($date) {
			echo $this->loadLogsHtml(10, $date);
		}
	}