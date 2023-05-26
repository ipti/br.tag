<?php
    Yii::import('application.modules.classdiary.services.*');
    /**
    * @property GetClassrooms $GetClassrooms
    */

    class GetClassrooms 
    {
        private $GetClassrooms;

        public function __construct($GetClassrooms= null)
        {
            $this->GetClassrooms = $GetClassrooms ?? new GetClassrooms();
        }
        public function exec(){
            $criteria = new CDbCriteria;
            if (Yii::app()->getAuthManager()->checkAccess('instructor', Yii::app()->user->loginInfos->id)) {
                $criteria->alias = "c";
                $criteria->join = ""
                    . " join instructor_teaching_data on instructor_teaching_data.classroom_id_fk = c.id "
                    . " join instructor_identification on instructor_teaching_data.instructor_fk = instructor_identification.id ";
                $criteria->condition = "c.school_year = :school_year and c.school_inep_fk = :school_inep_fk and instructor_identification.users_fk = :users_fk";
                $criteria->order = "name";
                $criteria->params = array(':school_year' => Yii::app()->user->year, ':school_inep_fk' => Yii::app()->user->school, ':users_fk' => Yii::app()->user->loginInfos->id);
            } else {
                $criteria->condition = "school_year = :school_year and school_inep_fk = :school_inep_fk";
                $criteria->order = "name";
                $criteria->params = $criteria->params = array(':school_year' => Yii::app()->user->year, ':school_inep_fk' => Yii::app()->user->school);
            }
            if(isset($criteria)){
                $response = $this->GetClassrooms->getClassrooms($criteria);
                return  $response; 
            }
        }
    }