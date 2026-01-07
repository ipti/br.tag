<ul class="list-timeline">
    <h3>Pendências Escola</h3>
    <div class="alert">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong></strong>
    </div>
    <?php if (!empty($log['school']['validate']['identification']) || !empty($log['school']['validate']['structure'])): ?>
        <div class="itens-censo">
            <h5>Escola</h5>
            <?php foreach ($log['school']['validate']['identification'] as $index => $identification) { ?>
                <?php if (!empty($identification)) { ?>
                    <li>
                        <span style="width: 20px;text-align: center;color:white;font-weight: bold" class="glyphicons activity-icon">
                            <i></i>
                        </span>
                        <span class="ellipsis" title="<?php echo @current($identification) ?>">
                            <?php echo str_replace("*", "", @SchoolIdentification::model()->getAttributeLabel(key($identification))) ?>
                            -
                            <?php echo @current($identification) ?>
                        </span>
                        <?php echo CHtml::link('- Corrigir', array('school/update', 'id' => $log['school']['info']['inep_id'], 'censo' => 1)); ?>
                        <?php @$dataValidation['school' . $log['school']['info']['inep_id']][] = current($identification); ?>
                        <div class="clearfix"></div>
                    </li>
                <?php } ?>
            <?php } ?>
            <?php foreach ($log['school']['validate']['structure'] as $index => $structure) { ?>
                <?php if (!empty($structure)) { ?>
                    <li>
                        <span style="width: 20px;text-align: center;color:white;font-weight: bold" class="glyphicons activity-icon">
                            <i></i>
                        </span>
                        <span class="ellipsis" title="<?php echo @current($structure) ?>">
                            <?php echo str_replace("*", "", @SchoolStructure::model()->getAttributeLabel(key($structure))) ?>
                            -
                            <?php echo @current($structure) ?>
                        </span>
                        <?php echo CHtml::link('- Corrigir', array('school/update', 'id' => $log['school']['info']['inep_id'], 'censo' => 1)); ?>
                        <?php @$dataValidation['school' . $log['school']['info']['inep_id']][] = current($structure); ?>
                        <div class="clearfix"></div>
                    </li>
                <?php } ?>
            <?php } ?>
        </div>
    <?php endif; ?>

    <?php
    foreach ($log['classroom'] as $index => $class):
        if (!empty($class['validate']['identification'])):
            ?>
            <div class="itens-censo">
                <h5>Turma - <?php echo $class['info']['name']; ?></h5>
                <?php foreach ($class['validate']['identification'] as $eindex => $classerror) { ?>
                    <li>
                        <span style="width: 20px;text-align: center;color:white;font-weight: bold" class="glyphicons activity-icon">
                            <i></i>
                        </span>
                        <span class="ellipsis" title="<?php echo current($classerror) ?>">
                            <?php echo str_replace("*", "", Classroom::model()->getAttributeLabel(key($classerror))) ?>
                            -
                            <?php echo current($classerror) ?>
                        </span>
                        <?php echo CHtml::link('- Corrigir', array(
                            'classroom/update',
                            'id' => $class['info']['id'],
                            'censo' => 1
                        )); ?>
                        <?php $dataValidation['classroom' . $class['info']['id']][] = current($classerror); ?>
                        <div class="clearfix"></div>
                    </li>
                <?php } ?>
            </div>
            <?php
        endif;
    endforeach;
    ?>

    <?php foreach ($log['instructor'] as $index => $instructor): ?>
        <div class="itens-censo">
            <h5>Professor - <?php echo $instructor['info']['name']; ?></h5>
            <?php foreach ($instructor['validate']['identification'] as $identification) { ?>
                <?php foreach ($identification as $instructorerror) { ?>
                    <li>
                        <span style="width: 20px;text-align: center;color:white;font-weight: bold" class="glyphicons activity-icon">
                            <i></i>
                        </span>
                        <span class="ellipsis" title="<?php echo current($instructorerror) ?>">
                            <?php echo str_replace("*", "", InstructorIdentification::model()->getAttributeLabel(key($instructorerror))) ?>
                            - <?php echo current($instructorerror) ?>
                        </span>
                        <?php echo CHtml::link('- Corrigir', array(
                            'instructor/update',
                            'id' => $instructor['info']['id'],
                            'censo' => 1
                        )); ?>
                        <?php $dataValidation['instructor' . $instructor['info']['id']][] = current($instructorerror); ?>
                        <div class="clearfix"></div>
                    </li>
                <?php } ?>
            <?php } ?>
            <?php foreach ($instructor['validate']['documents'] as $document): ?>
                <?php foreach ($document as $instructorerror): ?>
                    <li>
                        <span style="width: 20px;text-align: center;color:white;font-weight: bold" class="glyphicons activity-icon">
                            <i></i>
                        </span>
                        <span class="ellipsis" title="<?php echo current($instructorerror) ?>">
                            <?php echo str_replace("*", "", InstructorDocumentsAndAddress::model()->getAttributeLabel(key($instructorerror))) ?>
                            - <?php echo current($instructorerror) ?>
                        </span>
                        <?php echo CHtml::link('- Corrigir', array(
                            'instructor/update',
                            'id' => $instructor['info']['id'],
                            'censo' => 1
                        )); ?>
                        <?php $dataValidation['instructor' . $instructor['info']['id']][] = current($instructorerror); ?>
                        <div class="clearfix"></div>
                    </li>
                    <?php
                endforeach;
            endforeach;
            foreach ($instructor['validate']['variabledata'] as $variabledata):
                foreach ($variabledata['errors'] as $vberros):
                    ?>
                    <li>
                        <span style="width: 20px;text-align: center;color:white;font-weight: bold" class="glyphicons activity-icon">
                            <i></i>
                        </span>
                        <span class="ellipsis" title="<?php echo current($vberros) ?>">Na turma
                            <?php echo $variabledata['turma'] ?> |
                            <?php echo str_replace("*", "", InstructorVariableData::model()->getAttributeLabel(key($vberros))) ?>
                            - <?php echo current($vberros) ?>
                        </span>
                        <?php echo CHtml::link('- Corrigir', array(
                            'classroom/update',
                            'id' => $variabledata['id'],
                            'censo' => 1
                        )); ?>
                        <?php $dataValidation['classroom' . $variabledata['id']][] = current($vberros); ?>
                        <div class="clearfix"></div>
                    </li>
                    <?php
                endforeach;
            endforeach;
            ?>
        </div>
        <?php
    endforeach;
    foreach ($log['student'] as $student): ?>
        <div class="itens-censo">
            <h5>Aluno - <?php echo $student['info']['name']; ?></h5>
            <?php foreach ($student['validate']['identification'] as $identification): ?>
                <?php foreach ($identification as $studenterror): ?>
                    <li>
                        <span style="width: 20px;text-align: center;color:white;font-weight: bold" class="glyphicons activity-icon">
                            <i></i>
                        </span>
                        <span class="ellipsis" title="<?php echo current($studenterror) ?>">
                            <?php echo str_replace("*", "", StudentIdentification::model()->getAttributeLabel(key($studenterror))) ?>
                            -
                            <?php echo current($studenterror) ?>
                        </span>
                        <?php echo CHtml::link('- Corrigir', array(
                            'student/update',
                            'id' => $student['info']['id'],
                            'censo' => 1
                        )); ?>
                        <?php $dataValidation['student' . $student['info']['id']][] = current($studenterror); ?>
                        <div class="clearfix"></div>
                    </li>
                <?php endforeach;
            endforeach;
            foreach ($student['validate']['documents'] as $document):
                foreach ($document as $studenterror): ?>
                    <li>
                        <span style="width: 20px;text-align: center;color:white;font-weight: bold" class="glyphicons activity-icon">
                            <i></i>
                        </span>
                        <span class="ellipsis" title="<?php echo current($studenterror) ?>">
                            <?php echo str_replace("*", "", StudentDocumentsAndAddress::model()->getAttributeLabel(key($studenterror))) ?>
                            - <?php echo current($studenterror) ?>
                        </span>
                        <?php echo CHtml::link('- Corrigir', array(
                            'student/update',
                            'id' => $student['info']['id'],
                            'censo' => 1
                        )); ?>
                        <?php $dataValidation['student' . $student['info']['id']][] = current($studenterror); ?>
                        <div class="clearfix"></div>
                    </li>
                    <?php
                endforeach;
            endforeach;
            foreach ($student['validate']['enrollment'] as $enrollment):
                foreach ($enrollment['errors'] as $eberros): ?>
                    <li>
                        <span style="width: 20px;text-align: center;color:white;font-weight: bold" class="glyphicons activity-icon">
                            <i></i>
                        </span>
                        <span class="ellipsis" title="<?php echo current($eberros) ?>">Na turma
                            <?php echo $enrollment['turma'] ?> |
                            <?php echo str_replace("*", "", StudentEnrollment::model()->getAttributeLabel(key($eberros))) ?>
                            - <?php echo current($eberros) ?>
                        </span>
                        <?php echo CHtml::link('- Corrigir', $eberros["type"] === "batchUpdate"
                            ? array('classroom/batchupdatetotal', 'id' => $variabledata["id"], 'censo' => 1)
                            : array(
                                'enrollment/update',
                                'id' => $enrollment['id'],
                                'censo' => 1
                            )); ?>
                        <?php $dataValidation['enrollment' . $enrollment['id']][] = current($eberros); ?>
                        <div class="clearfix"></div>
                    </li>
                    <?php
                endforeach;
            endforeach;
            ?>
        </div>
    <?php endforeach; ?>
</ul>
