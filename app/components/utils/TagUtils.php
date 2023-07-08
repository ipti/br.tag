<?php 

class TagUtils extends CApplicationComponent {
    

    public static function isStageMinorEducation($stage){
        $REF_MINOR_STAGES = [
            '1', '2', '3', '4', '5', '6', '14', '15', '16'
        ];
        $stages = new CList($REF_MINOR_STAGES, true);
        Yii::log($stages->contains($stage), CLogger::LEVEL_ERROR);
    
        return $stages->contains($stage);
    }
}

?>