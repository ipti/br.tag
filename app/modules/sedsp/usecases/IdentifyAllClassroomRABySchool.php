<?php

Yii::import('application.modules.sedsp.datasources.sed.*');
Yii::import('application.modules.sedsp.datasources.tag.*');
Yii::import('application.modules.sedsp.models.*');

/**
 * @property StudentTAGDataSource $studentTAGDataSource
 * @property ClassStudentsRelationSEDDataSource $studentClassroomSEDDataSource
 */
class IdentifyAllClassroomRABySchool
{
    private  $studentTAGDataSource;
    private  $studentClassroomSEDDataSource;

    public function __construct($studentTAGDataSource = null, $studentClassroomSEDDataSource = null) {
        $this->studentTAGDataSource = $studentTAGDataSource ?? new StudentTAGDataSource();
        $this->studentClassroomSEDDataSource = $studentClassroomSEDDataSource ?? new ClassStudentsRelationSEDDataSource();
    }

    
    public function exec($schoolId, $year)
    {
        $schoolInepId = $this->adaptSchoolID($schoolId);
        $response = $this->studentClassroomSEDDataSource->getRelacaoClasses(new InRelacaoClasses(
            $year,
            SchoolMapper::mapToSEDInepId($schoolInepId),
            null,null,null,null
        ));
        return  new OutRelacaoClasses($response);
    }


    /**
     * Adapt School inep Id to SED API. This method remove the first
     * two characters from inepId
     * @param string $schoolId
     * @return string
     */
    private function adaptSchoolID($schoolId){
        $schoolIdText = strval($schoolId);
        return substr($schoolIdText, 2);
    }
}
