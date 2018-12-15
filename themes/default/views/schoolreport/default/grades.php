<?php

$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseScriptUrl . '/common/js/grades.js', CClientScript::POS_END);
$cs->registerScript("vars", "var getGradesUrl = '".Yii::app()->createUrl("schoolreport/default/getGrades", ['eid'=>$eid])."'", CClientScript::POS_BEGIN);
$cs->registerCssFile($baseScriptUrl . '/common/css/grades.css');

?>

<table id="grades" class="ui compact selectable  table">
    <thead>
        <tr>
            <th id="discipline" class="left aligned">Disciplina</th>
            <th class="center aligned">1ª</th>
            <th class="center aligned">1ª Rec.</th>
            <th class="center aligned">2ª</th>
            <th class="center aligned">2ª Rec.</th>
            <th class="center aligned">3ª</th>
            <th class="center aligned">3º Rec.</th>
            <th class="center aligned">4ª</th>
            <th class="center aligned">4ª Rec.</th>
            <th class="center aligned">Rec. Final</th>
            <th class="center aligned">Média Final</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($disciplines as $did=>$name){
        echo "<tr did='$did'>"
            ."<td class='left aligned'>$name</td>"
            ."<td></td>"
            ."<td></td>"
            ."<td></td>"
            ."<td></td>"
            ."<td></td>"
            ."<td></td>"
            ."<td></td>"
            ."<td></td>"
            ."<td></td>"
            ."<td></td>"
            ."</tr>";
    }
    ?>
    </tbody>
</table>
