<?php

Yii::import('application.modules.sedsp.datasources.sed.*');
Yii::import('application.modules.sedsp.datasources.tag.*');
Yii::import('application.modules.sedsp.models.*');

/**
 * @property StudentTAGDataSource $studentTAGDataSource
 * @property StudentClassroomSEDDataSource $studentClassroomSEDDataSource
 */
class IdentifyAllClassroomRABySchool
{
    private  $studentTAGDataSource;
    private  $studentClassroomSEDDataSource;

    public function __construct($studentTAGDataSource = null, $studentClassroomSEDDataSource = null) {
        $this->studentTAGDataSource = $studentTAGDataSource ?? new StudentTAGDataSource();
        $this->studentClassroomSEDDataSource = $studentClassroomSEDDataSource ?? new StudentClassroomSEDDataSource();
    }
    public function exec($school_id, $year)
    {   
        $school_id_sed = $this->adaptSchoolID($school_id);
        $response = $this->studentClassroomSEDDataSource->getRelationClassrooms($school_id_sed, $year);
        $escolaTurmas  = new EscolaTurmas($response);
        return $escolaTurmas;
    }


    /**
     * Adapt School inep Id to SED API. This method remove the first
     * two characters from inepId
     * @param string $school_id
     * @return string
     */
    private function adaptSchoolID($school_id){
        $school_id_text = strval($school_id);
        $school_id_sed = substr($school_id_text, 2); 
        return $school_id_sed;
    }
}
