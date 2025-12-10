
<div class="ui styled fluid accordion">
<?php
    foreach (array_reverse(yii::app()->user->enrollments, true) as $year => $enrolls) {
        $active = '';
        if ($year == date('Y')) {
            $active = 'active';
            $statusText = 'Cursando';
            $statusColor = '';
            $statusIcon = 'edit';
        } else {
            $statusText = 'Concluído';
            $statusColor = 'teal';
            $statusIcon = 'check';
        }
        echo '<div class="' . $active . ' title">
                <i class="dropdown icon"></i>
                    Ano ' . $year . '
              </div>
                <div class="' . $active . ' content ">
                    <div class="ui cards">';
        foreach ($enrolls as $eid => $info) {
            $eName = ucwords(strtolower($info['name']));
            $cid = $info['classroom_id'];
            $cName = $info['classroom_name'];
            echo '<div class="card">
                <div class="content">
                    <div class="ui small header">' . $eName . '</div>
                    <div class="meta"></div>
                    <div class="description">
                        <p>Turma: ' . $cName . '</p>
                    </div>
                    <div class="ui ' . $statusColor . ' right ribbon label">
                        <i class="' . $statusIcon . ' icon"></i> ' . $statusText . '
                    </div>
                </div>
                <div class="extra ui inverted tag-blue segment content">
                    <div class="left floated">
                        <a href="' . Yii::app()->createUrl('schoolreport/default/frequency', ['eid' => $eid]) . '">
                            <i class="wait icon"></i>
                            Frequência
                        </a>
                    </div>
                    <div class="right floated">
                        <a href="' . Yii::app()->createUrl('schoolreport/default/grades', ['eid' => $eid]) . '">
                            <i class="book icon"></i>
                            Notas
                        </a>
                    </div>
                </div>
            </div>
            ';
        }
        echo '</div>
            </div>';
    }
?>
</div>
