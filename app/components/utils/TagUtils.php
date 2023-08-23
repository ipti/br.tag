<?php 

class TagUtils extends CApplicationComponent {
    

    public static function isStageMinorEducation($stage){
        $REF_MINOR_STAGES = [
            '1', '2', '3', '4', '5', '6', '14', '15', '16'
        ];
        $stages = new CList($REF_MINOR_STAGES, true);
        return $stages->contains($stage);
    }

    public static function isInstance($instance){
        
        if(is_array($instance)){
            $instances = array_map(function ($e){
                return strtoupper($e);
            }, $instance);

            return in_array(strtoupper(INSTANCE), $instances);
        }

        return strtoupper(INSTANCE) === strtoupper($instance);
        
    }    
}

?>