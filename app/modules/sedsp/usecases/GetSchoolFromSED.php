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
    public function exec($school_name, $school_mun)
    {
        $response = $this->schoolSEDDataSource->getSchool($school_name, $school_mun);
        $content = $response->getBody()->getContents();
        $school_tag = SchoolMapper::parseToTAGSchool($content);
        return $school_tag;
    }
}
