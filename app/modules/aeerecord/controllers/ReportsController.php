<?php

class ReportsController extends Controller
{
    public function actionAeeRecordReport($id)
    {
        $school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);

        $sql = 'SELECT sar.*, si.name AS studentName, c.name AS classroomName, ii.name AS instructorName
                from student_aee_record sar
                join student_identification si on si.id = sar.student_fk
                join classroom c on c.id = sar.classroom_fk
                join instructor_identification ii on ii.id = sar.instructor_fk
                WHERE sar.id = :record_id';

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':record_id', $id, PDO::PARAM_INT);

        $aeeRecord = $command->queryAll();

        $this->layout = 'webroot.themes.default.views.layouts.reportsclean';
        $this->render(
            'aeeRecordReport',
            [
                'aeeRecord' => $aeeRecord[0],
                'school' => $school,
            ]
        );
    }
}
