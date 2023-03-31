<?php

Yii::import('application.modules.sedsp.datasources.sed.*');
Yii::import('application.modules.sedsp.datasources.tag.*');
Yii::import('application.modules.sedsp.models.*');

/**
 * @property StudentTAGDataSource $studentTAGDataSource
 * @property StudentSEDDataSource $studentSEDDataSource
 */
class AddRACodeToTAG
{
    private  $studentTAGDataSource;
    private  $studentSEDDataSource;

    function __construct($studentTAGDataSource = null, $studentSEDDataSource = null) {
        $this->studentTAGDataSource = $studentTAGDataSource ?? new StudentTAGDataSource();
        $this->studentSEDDataSource = $studentSEDDataSource ?? new StudentSEDDataSource();
    }

    /**
     * Summary of exec
     * @param int $tag_student_id StudentIdentificantion Id from TAG
     * @return StudentIdentification
     */
    public function exec($tag_student_id,$racode)
    {
        // Get Student From TAG database
        $student_tag = $this->studentTAGDataSource->getStudent($tag_student_id);
        $student_tag->gov_id = $racode;
        $student_tag->update(array('gov_id'));
        return $student_tag;
    }
}
