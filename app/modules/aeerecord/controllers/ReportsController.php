<?php
class ReportsController extends Controller
{
    public function actionAeeRecordReport() {
        $school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);
        $schoolCity = EdcensoCity::model()->findByPk($school->edcenso_city_fk)->name;
        $schoolName = $school->name;

        $this->layout = 'webroot.themes.default.views.layouts.reportsclean';
        $this->render('AeeRecordReport',
        array(
            "schoolCity" => $schoolCity,
            "schoolName"=>$schoolName,
        ));
    }
}
