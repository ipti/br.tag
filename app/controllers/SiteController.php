<?php

class SiteController extends Controller
{

    //@done S2 -FAzer Cadastro de usuário
    //@done s1 -Limitar a escolha de escolas apenas para o Administrador

    /**
     * Declares class-based actions.
     */
    public $layout = 'fullmenu';

    public function accessRules()
    {
        return [
            [
                'allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => ['changeschool', 'changeyear', 'loadMoreLogs', 'loadMoreWarns'],
                'users' => ['@'],
            ],
        ];
    }

    public function actions()
    {
        return [
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => [
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
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
    public function actionIndex()
    {

        // renders the view file 'protected/views/site/index.php'
        // using the default layout 'protected/views/layouts/main.php'
        if (Yii::app()->user->isGuest) {
            $this->redirect(yii::app()->createUrl('site/login'));
        }

        $this->loadLogsHtml(5);

        if (TagUtils::isInstructor()) {
            $this->render('index', ["htmlLogs" => $this->loadLogsHtml(8)]);
        } else {
            $this->render('index', ["htmlLogs" => $this->loadLogsHtml(8)]);
        }


    }



    /**
     * This is the action to handle external exceptions.
     */
    public function actionError()
    {
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
    public function actionContact()
    {
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
    public function actionLogin()
    {
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
        $year = date('Y');
        $model->year = 0;
        $years = array();
        if (date('m') >= 11) {
            $year = $year + 1;
            $model->year = 1;
        }
        for ($i = $year; $i >= 2014; $i--) {
            $years[$i] = $i;
        }



        $this->render('login', ['model' => $model, 'years' => $years]);
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout()
    {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

    public function actionChangeSchool()
    {
        if (isset($_POST['UsersSchool']['school_fk']) && !empty($_POST['UsersSchool']['school_fk'])) {
            Yii::app()->user->school = $_POST['UsersSchool']['school_fk'];
        } else if (isset($_POST['SchoolIdentification']['inep_id']) && !empty($_POST['SchoolIdentification']['inep_id'])) {
            Yii::app()->user->school = $_POST['SchoolIdentification']['inep_id'];
        }

        // Yii::app()->cache->delete("fullmenu");

        echo '<script>history.go(-1);</script>';
        exit;
    }

    public function actionChangeYear()
    {
        if (isset($_POST['years']) && !empty($_POST['years'])) {
            Yii::app()->user->year = $_POST['years'];
        }

        // Yii::app()->cache->flush();

        echo '<script>history.go(-1);</script>';
        exit;
    }

    private function loadLogsHtml($limit, $date = null)
    {
        $baseUrl = Yii::app()->theme->baseUrl;
        $isInstructor = Yii::app()->getAuthManager()->checkAccess('instructor', Yii::app()->user->loginInfos->id);
        $criteria = new CDbCriteria();
        $criteria->compare('school_fk', Yii::app()->user->school);
        $criteria->order = 'date DESC';
        $criteria->limit = $limit;

        if ($isInstructor) {
            $criteria->compare('user_fk', Yii::app()->user->loginInfos->id);
        }

        if ($date !== null) {
            $criteria->addCondition("date < STR_TO_DATE(:date, '%d/%m/%Y à\s %H:%i:%s')");
            $criteria->params[':date'] = $date;
        }

        $logs = Log::model()->findAll($criteria);


        $html = "";

        if (count($logs) > 0) {

            $bgColor = 'gray';
            foreach ($logs as $log) {
                $elements = $log->loadIconsAndTexts($log);
                $bgColor = 'gray' == $bgColor ? 'blue' : 'gray';
                $date = date("d/m/Y à\s H:i:s", strtotime($log->date));
                $html .= '<li class="row justify-content--start  home-page-table-item ' . $bgColor . '" title=\'' . $elements["text"] . '\'>'
                    . '<div  class="column align-items--center" style="max-width:815px;text-overflow: ellipsis;">'
                    . '<img style="background-color:' . $elements["color"] . '" src="' . Yii::app()->theme->baseUrl . '/img/homePageIcons/' . $elements["icon"] . '.svg"/>'
                    . $elements["text"] . '</div>'
                    . '<div class="column">'
                    . '<div class="log-date">' . $date . '</div>'
                    . '<div class="log-author">' . $log->userFk->name . ' - </div>'
                    . '</div>'
                    . '</li>';
            }
        } else {
            $html = '<div class="no-recent-activitive t-badge-info" id="no-recent-activitives"><span class="t-info_positive t-badge-info__icon"></span>Não há atividades recentes.</div>';
        }

        return $html;
    }

    public function actionLoadMoreLogs($date)
    {
        echo $this->loadLogsHtml(10, $date);
    }

    private function actionLoadWarnsHtml(int $limit)
    {
        $warns = [];

        //Verifica a existência de turmas na escola
        $listSchoolClassrooms = Classroom::model()->findallByAttributes(["school_inep_fk" => yii::app()->user->school, "school_year" => Yii::app()->user->year]);
        if (count($listSchoolClassrooms) == 0) {
            $schoolModel = SchoolIdentification::model()->findByAttributes(['inep_id' => yii::app()->user->school]);
            $warning = 'Escola <b>' . $schoolModel->name . '</b> está sem turmas cadastradas.';

            $htmlpart = '<li class="row justify-content--start  home-page-table-item blue" title=\'' . $warning . '\'>'
                . '<div  class="column align-items--center warn-div" style="text-overflow: ellipsis;">'
                . '<img style="background-color:orange" src="' . Yii::app()->theme->baseUrl . '/img/homePageIcons/turmas.svg"/>'
                . '<span>' . $warning . '</span>'
                . '</div>'
                . '</li>';

            array_push($warns, $htmlpart);
        } else {
            foreach ($listSchoolClassrooms as $classroom) {
                //Se houver turma, verificar se existe etapa vinculada à turma
                $stage = $classroom->edcensoStageVsModalityFk;
                if ($stage == null) {
                    $warning = 'Turma <b>' . $classroom->name . '</b> está sem etapa.';
                    $htmlpart = '<li class="row justify-content--start  home-page-table-item blue" title=\'' . $warning . '\'>'
                        . '<div  class="column align-items--center warn-div" style="text-overflow: ellipsis;">'
                        . '<img style="background-color:orange" src="' . Yii::app()->theme->baseUrl . '/img/homePageIcons/plano_de_aula.svg"/>'
                        . '<span>' . $warning . '</span>'
                        . '</div>'
                        . '</li>';
                    array_push($warns, $htmlpart);
                } else {
                    //Se houver etapa, verificar matriz curricular
                    $listCurricularMatrixs = $stage->curricularMatrixes;
                    if (count($listCurricularMatrixs) == 0) {
                        $warning = 'Etapa <b>' . $stage->name . '</b> da turma <b>' . $classroom->name . '</b> está sem matriz curricular.';
                        $htmlpart = '<li class="row justify-content--start  home-page-table-item blue" title=\'' . $warning . '\'>'
                            . '<div  class="column align-items--center warn-div" style="text-overflow: ellipsis;">'
                            . '<img style="background-color:orange" src="' . Yii::app()->theme->baseUrl . '/img/homePageIcons/matriz_curricular.svg"/>'
                            . '<span>' . $warning . '</span>'
                            . '</div>'
                            . '</li>';
                        array_push($warns, $htmlpart);
                    }

                    //Se houver etapa, verificar se existe estrutura de notas
                    $listGradeUnities = $stage->gradeUnities;
                    if (count($listGradeUnities) == 0) {
                        $warning = 'Etapa <b>' . $stage->name . '</b> da turma <b>' . $classroom->name . '</b> está sem estrutura de notas.';
                        $htmlpart = '<li class="row justify-content--start  home-page-table-item blue" title=\'' . $warning . '\'>'
                            . '<div  class="column align-items--center warn-div" style="text-overflow: ellipsis;">'
                            . '<img style="background-color:orange; color:black;" src="' . Yii::app()->theme->baseUrl . '/img/homePageIcons/notas.svg"/>'
                            . '<span>' . $warning . '</span>'
                            . '</div>'
                            . '</li>';
                        array_push($warns, $htmlpart);
                    }

                }

                //Se houver turma, verificar se existe calendario vinculado a ela
                $calendar = $classroom->calendarFk;
                if ($calendar == null) {
                    $warning = 'Turma <b>' . $classroom->name . '</b> não possui calendário vinculado.';
                    $htmlpart = '<li class="row justify-content--start  home-page-table-item blue" title=\'' . $warning . '\'>'
                        . '<div  class="column align-items--center warn-div" style="text-overflow: ellipsis;">'
                        . '<img style="background-color:orange" src="' . Yii::app()->theme->baseUrl . '/img/homePageIcons/calendario.svg"/>'
                        . '<span>' . $warning . '</span>'
                        . '</div>'
                        . '</li>';
                    array_push($warns, $htmlpart);
                }

                //Se houver turma, verificar se existe quadro de horários
                $listSchedules = $classroom->schedules;
                if (count($listSchedules) == 0) {
                    $warning = 'Turma <b>' . $classroom->name . '</b> está sem quadro de horários.';
                    $htmlpart = '<li class="row justify-content--start  home-page-table-item blue" title=\'' . $warning . '\'>'
                        . '<div  class="column align-items--center warn-div" style="text-overflow: ellipsis;">'
                        . '<img style="background-color:orange" src="' . Yii::app()->theme->baseUrl . '/img/homePageIcons/quadro_de_horario.svg"/>'
                        . '<span>' . $warning . '</span>'
                        . '</div>'
                        . '</li>';
                    array_push($warns, $htmlpart);
                }

                //Se houver turma, verificar se existe professor
                $listInstructors = $classroom->instructorTeachingDatas;
                if (count($listInstructors) == 0) {
                    $warning = 'Turma <b>' . $classroom->name . '</b> está sem professores.';
                    $htmlpart = '<li class="row justify-content--start  home-page-table-item blue" title=\'' . $warning . '\'>'
                        . '<div  class="column align-items--center warn-div" style="text-overflow: ellipsis;">'
                        . '<img style="background-color:orange" src="' . Yii::app()->theme->baseUrl . '/img/homePageIcons/professores.svg"/>'
                        . '<span>' . $warning . '</span>'
                        . '</div>'
                        . '</li>';
                    array_push($warns, $htmlpart);
                }

                //Se houver turma, verificar se existe aluno matriculado
                $listStudentEnrollments = $classroom->studentEnrollments;
                if (count($listStudentEnrollments) == 0) {
                    $warning = 'Turma <b>' . $classroom->name . '</b> está sem alunos matriculados.';
                    $htmlpart = '<li class="row justify-content--start  home-page-table-item blue" title=\'' . $warning . '\'>'
                        . '<div  class="column align-items--center warn-div" style="text-overflow: ellipsis;">'
                        . '<img style="background-color:orange" src="' . Yii::app()->theme->baseUrl . '/img/homePageIcons/matricula.svg"/>'
                        . '<span>' . $warning . '</span>'
                        . '</div>'
                        . '</li>';
                    array_push($warns, $htmlpart);
                }

            }
        }

        if (count($warns) == 0) {
            $this->renderPartial("_warns",[
                "total" => 0,
                "limit" => $limit,
                "html" => '<div class="no-recent-activitive t-badge-info" id="no-recent-warnings"><span class="t-info_positive t-badge-info__icon"></span>Não há cadastros pendentes.</div>'
            ]);
        } else {
            $html = "";
            $count = min([$limit, count($warns)]) ;
            for ($i = 0; $i < $count; $i++) {
                $html .= $warns[$i];
            }
            $this->renderPartial("_warns", [
                "total" => count($warns),
                "limit" => $limit,
                "html" => $html
            ]);
        }

        Yii::app()->end();
    }

    public function actionLoadLineChartData()
    {
        $year = $_POST["year"];
        $school = Yii::app()->user->school;
        $sql = "select " .
            "month(date) as month, " .
            "(select  count(*) from log where crud = 'C' and reference = 'school' and year(date) = $year and month(date) = month and school_fk = $school) as schools, " .
            "(select  count(*) from log where crud = 'C' and reference = 'classroom' and year(date) = $year and month(date) = month and school_fk = $school) as classrooms, " .
            "(select  count(*) from log where crud = 'C' and reference = 'instructor' and year(date) = $year and month(date) = month and school_fk = $school) as instructors, " .
            "(select  count(*) from log where crud = 'C' and reference = 'student' and year(date) = $year and month(date) = month and school_fk = $school) as students " .
            "from log " .
            "group by month(date)";
        $chartData = Yii::app()->db->schema->commandBuilder->createSqlCommand($sql)->queryAll();
        $monthsFilled = [];
        foreach ($chartData as $data) {
            array_push($monthsFilled, $data["month"]);
        }
        for ($i = 1; $i <= 12; $i++) {
            if (!in_array($i, $monthsFilled)) {
                $emptyMonth = [
                    "month" => $i,
                    "schools" => 0,
                    "classrooms" => 0,
                    "instructors" => 0,
                    "students" => 0,
                ];
                array_push($chartData, $emptyMonth);
            }
        }

        function cmp($a, $b)
        {
            return $a["month"] - $b["month"];
        }

        usort($chartData, "cmp");

        foreach ($chartData as $key => $data) {
            switch ($data["month"]) {
                case "1":
                    $chartData[$key]["month"] = "Janeiro";
                    break;
                case "2":
                    $chartData[$key]["month"] = "Fevereiro";
                    break;
                case "3":
                    $chartData[$key]["month"] = "Março";
                    break;
                case "4":
                    $chartData[$key]["month"] = "Abril";
                    break;
                case "5":
                    $chartData[$key]["month"] = "Maio";
                    break;
                case "6":
                    $chartData[$key]["month"] = "Junho";
                    break;
                case "7":
                    $chartData[$key]["month"] = "Julho";
                    break;
                case "8":
                    $chartData[$key]["month"] = "Agosto";
                    break;
                case "9":
                    $chartData[$key]["month"] = "Setembro";
                    break;
                case "10":
                    $chartData[$key]["month"] = "Outubro";
                    break;
                case "11":
                    $chartData[$key]["month"] = "Novembro";
                    break;
                case "12":
                    $chartData[$key]["month"] = "Dezembro";
                    break;
            }
        }
        echo json_encode($chartData);
    }

    public function actionLoadCylinderChartData()
    {
        $school = Yii::app()->user->school;
        $year = $_POST["year"];
        $sql = "select " .
            "1 as schools, " .
            "(select count(*) from classroom where school_inep_fk = $school and school_year = $year) as classrooms, " .
            "(select count(*) from instructor_identification where school_inep_id_fk = $school) as instructors, " .
            "(select count(*) from student_identification where school_inep_id_fk = $school) as students";
        $chartData = Yii::app()->db->schema->commandBuilder->createSqlCommand($sql)->queryRow();
        echo json_encode($chartData);
    }

    public function actionLoadPieChartData()
    {
        $school = Yii::app()->user->school;
        $year = $_POST["year"];
        $sql = "select " .
            "(select count(*) from student_identification si where si.school_inep_id_fk = $school) as students, " .
            "(select distinct count(*) from student_identification si join student_enrollment se on si.id = se.student_fk join classroom c on c.id = se.classroom_fk where c.school_year = $year and si.school_inep_id_fk = $school) as enrollments";
        $chartData = Yii::app()->db->schema->commandBuilder->createSqlCommand($sql)->queryRow();
        echo json_encode($chartData);
    }


    public function actionViewFileLogs($page = 1)
    {
        // Caminho para o arquivo de log
        $logFilePath = Yii::app()->getRuntimePath() . '/' . INSTANCE . '/application.log';

        // Número de linhas por página
        $linesPerPage = 100;

        if (file_exists($logFilePath)) {
            // Lê todas as linhas do arquivo de log
            $lines = array_reverse(file($logFilePath));

            // Conta o número total de páginas
            $totalLines = count($lines);
            $totalPages = ceil($totalLines / $linesPerPage);

            // Certifica-se de que a página atual esteja dentro dos limites
            $page = max(1, min($totalPages, (int) $page));

            // Calcula as linhas a serem exibidas nesta página
            $start = ($page - 1) * $linesPerPage;
            $logContent = array_slice($lines, $start, $linesPerPage);

            // Gera a string do conteúdo do log para exibição
            $logContent = implode("", $logContent);
        } else {
            $logContent = "O arquivo de log não foi encontrado.";
            $totalPages = 1;
            $page = 1;
        }

        // Renderiza a view e passa os parâmetros para a página
        $this->render('viewLogs', array(
            'logContent' => $logContent,
            'totalPages' => $totalPages,
            'currentPage' => $page,
        ));
    }

    public function actionDownloadFileLog(): void
    {
        // Caminho para o arquivo de log
        $logFilePath = Yii::app()->getRuntimePath() . '/' . INSTANCE . '/application.log';
        $this->download($logFilePath);
    }

    private function download($arquivo)
    {
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream;");
        header("Content-Length:" . filesize($arquivo));
        header("Content-disposition: attachment; filename=" . $arquivo);
        header("Pragma: no-cache");
        header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
        header("Expires: 0");
        readfile($arquivo);
        flush();
    }
}
