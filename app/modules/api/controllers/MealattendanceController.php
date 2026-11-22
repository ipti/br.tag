<?php

/**
 * POST api/mealattendance/save: presença do dia para a merenda, enviada pelo
 * reconhecimento facial da escola.
 */
class MealattendanceController extends ApiController
{
    public function actionSave()
    {
        $this->requireMethod('POST');
        $client = $this->authenticate();
        $schoolInep = $this->getClientSchoolInep();

        $result = (new SchoolMealAttendanceService())->save($this->getJsonBody(), $client, $schoolInep);

        $this->respond($result);
    }
}
