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
    private $studentTAGDataSource;
    private $studentSEDDataSource;

    public function __construct($studentTAGDataSource = null, $studentSEDDataSource = null)
    {
        $this->studentTAGDataSource = $studentTAGDataSource ?? new StudentTAGDataSource();
        $this->studentSEDDataSource = $studentSEDDataSource ?? new StudentSEDDataSource();
    }

    /**
     * Summary of exec
     * @param int $tagStudentId StudentIdentificantion Id from TAG
     * @return StudentIdentification
     */
    public function exec($tagStudentId, $racode)
    {
        // Get Student From TAG database
        $studentTag = $this->studentTAGDataSource->getStudent($tagStudentId);
        $studentTag->gov_id = $racode;

        if ($studentTag->update()) {
            return $studentTag;
        }

        throw new CHttpException(404, 'The requested student does not updated.');
    }
}
