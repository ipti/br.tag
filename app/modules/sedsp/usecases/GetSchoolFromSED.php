<?php

Yii::import('application.modules.sedsp.datasources.sed.*');
Yii::import('application.modules.sedsp.datasources.tag.*');
Yii::import('application.modules.sedsp.models.*');
Yii::import('application.modules.sedsp.mappers.*');

/**
 * @property SchoolSEDDataSource $schoolSEDDataSource
 */
class GetSchoolFromSED
{
    private  $schoolSEDDataSource;

    public function __construct($schoolSEDDataSource = null)
    {
        $this->schoolSEDDataSource = $schoolSEDDataSource ?? new SchoolSEDDataSource();
    }
    public function exec($schoolName, $schoolMun)
    {
        $response = $this->schoolSEDDataSource->getSchool($schoolName, $schoolMun);
        $content  = $response->getBody()->getContents();
        return SchoolMapper::parseToTAGSchool($content);
    }
}
