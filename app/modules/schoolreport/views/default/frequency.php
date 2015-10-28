<?php

$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;
$cs = Yii::app()->getClientScript();
//$cs->registerScriptFile($baseScriptUrl . '/common/js/grades.js', CClientScript::POS_END);
//$cs->registerScript("vars", "var getGradesUrl = '".Yii::app()->createUrl("schoolreport/default/getGrades", ['eid'=>$eid])."'", CClientScript::POS_BEGIN);
//$cs->registerCssFile($baseScriptUrl . '/common/css/grades.css');

?>

<table id="grades" class="ui compact selectable  table">
    <thead>
    <tr>
        <th id="discipline" class="left aligned">Disciplina</th>
        <th class="center aligned">Jan</th>
        <th class="center aligned">Fev</th>
        <th class="center aligned">Mar</th>
        <th class="center aligned">Abr</th>
        <th class="center aligned">Mai</th>
        <th class="center aligned">Jun</th>
        <th class="center aligned">Jul</th>
        <th class="center aligned">Ago</th>
        <th class="center aligned">Set</th>
        <th class="center aligned">Out</th>
        <th class="center aligned">Nov</th>
        <th class="center aligned">Dec</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($frequency as $did=>$f){
        $dName = $f["name"];
        $months = $f["months"];
        echo "<tr did='$did'>"
            ."<td class='left aligned'>$dName</td>";
        foreach($months as $month=>$m){
            $color = '';
            if(isset($m['faults'], $m['classes'])){
                $faults = $m['faults'];
                $classes = $m['classes'];
                $presence = $classes - $faults;
                $percent = ceil($presence/$classes * 1000)/10;
                if($percent >= 90) $color = 'green';
                if($percent >= 75 && $percent < 90) $color = 'blue';
                if($percent >= 50 && $percent < 75) $color = 'yellow';
                if($percent < 50) $color = 'red';
                $percent .= "%";
            }else{
                $percent = "-";
            }

            echo "<td class='center aligned'><span class='ui $color label'>$percent</span></td>";
        }
        echo "</tr>";
    }
    ?>
    </tbody>
</table>
