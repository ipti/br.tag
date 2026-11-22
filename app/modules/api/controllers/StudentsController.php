<?php

/**
 * GET api/students/index: alunos da escola do cliente autenticado.
 */
class StudentsController extends ApiController
{
    public function actionIndex()
    {
        $this->requireMethod('GET');
        $this->authenticate();
        $schoolInep = $this->getClientSchoolInep();

        $year = Yii::app()->request->getQuery('ano', date('Y'));
        $year = filter_var($year, FILTER_VALIDATE_INT, ['options' => ['min_range' => 2000, 'max_range' => 2100]]);
        if ($year === false) {
            throw new ApiException(422, 'dados_invalidos', 'O parâmetro ano precisa ser um ano válido, ex.: 2026.');
        }

        $students = (new ApiStudentService())->listBySchool($schoolInep, $year);

        $this->respond([
            'escola_id' => $schoolInep,
            'ano' => $year,
            'total' => count($students),
            'alunos' => $students,
        ]);
    }
}
