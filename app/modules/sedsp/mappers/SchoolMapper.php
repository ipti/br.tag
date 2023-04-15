<?php

Yii::import('application.modules.sedsp.models.*');
class SchoolMapper 
    {
        public static function parseToTAGSchool($content)
        {
            $response = json_decode($content);
            $result = [];

            $result["SchoolIdentification"] = $school_tag;
            $result["SchoolStructure"] = $school_structure_tag;

            return $result;
        }
    }
    


?>