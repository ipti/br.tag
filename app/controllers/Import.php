<?php

function import(){
        $filedir = '/home/ipti009/Área de Trabalho/2013_98018493.TXT';
        $mode = 'r';

        $file = fopen($filedir, $mode);
        if ($file == false)
            die('O arquivo não existe.');
        $linha = 0;
        $arrayLinha = [];
        while (true) {
            $line = fgets($file);
            if ($line == null)
                break;
            $reg = $line[0] . $line[1];
            $seq = explode("|", $line);
            
            $arrayLinha[$linha++] = $seq;
        }

        $totalLines = count($arrayLinha)-1;
        $str_fields = "";
        for ($line = 0; $line <= $totalLines; $line++) {
            $totalColumns = count($arrayLinha[$line])-2;
            for ($column = 0; $column <= $totalColumns; $column++) {
                if ($column == 0) {
                    $str_fields.= "(";
                }
                $val = "\"".$arrayLinha[$line][$column]."\"";
                
                if ($column == $totalColumns) {
                    if ($line == ($totalLines)) {
                        $str_fields.= $val. ");";
                    } else {
                        $str_fields.= $val. "),";
                    }
                } else {
                    $str_fields.= $val. ",";
                }
            }
        };
        echo "INSERT INTO student_identification VALUES " . $str_fields;
        //Yii::app()->db->createCommand("INSERT INTO student_identification VALUES" . $str_fields)->queryAll();

    
}
function arrangeValues($initialColumn, $finalColumn, $line){
    $return = "";
    for ($column = $initialColumn; $column <= $finalColumn; $column++) {

        if ($column == $initialColumn) {
            $return .= "(";
        }
        $value = "\"".$line[$column]."\"";

        if ($column == $finalColumn) {
            $return.= $value. ")";
        } else {
            $return.= $value. ", ";
        }
    }
    return $return;
}

function getPreInsertValues($regType, $column, $line){
    
    $return = [];
    $return[0] = $column;
    $return[1] = NULL;
    
    
    switch ($regType) {
        case '00': {
            if($column == 27){
                $initialColumn == 27;
                $finalColumn = 31;
                
                $return[0] = $finalColumn;
                $return[1] = arrangeValues($initialColumn, $finalColumn, $line);
            }
            break;
        }
        case '10': {
            echo "INSERT INTO school_structure VALUES " . $lines;
            break;
        }
        case '20': {
            echo "INSERT INTO classroom VALUES " . $lines;
            break;
        }
        case '30': {
            echo "INSERT INTO instructor_identification VALUES " . $lines;
            break;
        }
        case '40': {
            echo "INSERT INTO instructor_documents_and_address VALUES " . $lines;
            break;
        }
        case '50': {
            echo "INSERT INTO instructor_variable_data VALUES " . $lines;
            break;
        }
        case '51': {
            echo "INSERT INTO instructor_teaching_data VALUES " . $lines;
            break;
        }
        case '60': {
            echo "INSERT INTO student_identification VALUES " . $lines;
            break;
        }
        case '70': {
            echo "INSERT INTO student_documents_and_address VALUES " . $lines;
            break;
        }
        case '80': {
            echo "INSERT INTO student_enrollment VALUES " . $lines;
            break;
        }
    }
    
    
    return $return;
}


?>
