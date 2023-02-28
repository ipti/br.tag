<div id="mainPage" class="main" style="margin-top:40px; padding: 10px">
    <?php
    $this->setPageTitle('TAG - ' . Yii::t('default', 'Censo'));
    $title = Yii::t('default', 'Create a new User');
    $contextDesc = Yii::t('default', 'Available actions that may be taken on User.');
    $this->menu = array(
        array('label' => Yii::t('default', 'List User'),
            'url' => array('index'),
            'description' => Yii::t('default', 'This action list all User, you can search, delete and update')),
    );
    $themeUrl = Yii::app()->theme->baseUrl;
    $cs = Yii::app()->getClientScript();
    $cs->registerCssFile($themeUrl . '/css/template2.css');  
    ?>
    <style type="text/css">
        .widget-timeline .widget-body:before {
            background: none !important;
        }

        .widget-timeline ul.list-timeline li {
            margin-bottom: 2px;
        }

        .itens-censo {
            display: none;
            margin-bottom: 10px;
        }

        .ellipsis {
            max-width: calc(100% - 40px) !important;
        }
    </style>
    <div class="clearfix"></div>
    <div class="widget widget-4 widget-tabs-icons-only widget-timeline margin-bottom-none">

        <div class="widget-body">
        </div>

        <?php if (Yii::app()->user->hasFlash('success')) { ?>
            <div class="alert alert-success">
                <?php echo Yii::app()->user->getFlash('success') ?>
            </div>
        <?php } else if (Yii::app()->user->hasFlash('error')) { ?>
            <div class="alert alert-error">
                <?php echo Yii::app()->user->getFlash('success') ?>
            </div>
        <?php } ?>
        <div style="display: flex;">
            <a href="<?= CHtml::normalizeUrl(array('censo/export')) ?>"
               class="t-button-primary"> <?= Yii::t('default', 'Export Now') ?>
            </a>
        </div>
        <!-- Widget Heading END -->
        <?php
        $dataValidation = [];
        $timeExpiration = 60 * 60 * 12;
        ?>
        <div class="widget-body">
            <div class="tab-content">

                <!-- Filter Users Tab -->
                <div class="tab-pane active" id="filterUsersTab">
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
                                <span style="width: 20px;text-align: center;color:white;font-weight: bold"
                                      class="glyphicons activity-icon">
                                    <i></i>
                                </span>
                                            <span class="ellipsis">
                                    <?php echo str_replace("*", "", @SchoolIdentification::model()->getAttributeLabel(key($identification))) ?>
                                                    -
                                                    <?php echo @current($identification) ?>
                                                <?php echo CHtml::link('- Corrigir', array('school/update', 'id' => $log['school']['info']['inep_id'], 'censo' => 1)); ?>
                                </span>
                                            <?php @$dataValidation['school' . $log['school']['info']['inep_id']][] = current($identification); ?>
                                            <div class="clearfix"></div>
                                        </li>
                                    <?php } ?>
                                <?php } ?>
                                <?php foreach ($log['school']['validate']['structure'] as $index => $structure) { ?>
                                    <?php if (!empty($structure)) { ?>
                                        <li>
                                <span style="width: 20px;text-align: center;color:white;font-weight: bold"
                                      class="glyphicons activity-icon">
                                    <i></i>
                                </span>
                                            <span class="ellipsis">
                                    <?php echo str_replace("*", "", @SchoolStructure::model()->getAttributeLabel(key($structure))) ?>
                                                    -
                                                    <?php echo @current($structure) ?>
                                                <?php echo CHtml::link('- Corrigir', array('school/update', 'id' => $log['school']['info']['inep_id'], 'censo' => 1)); ?>
                                </span>
                                            <?php @$dataValidation['school' . $log['school']['info']['inep_id']][] = current($structure); ?>
                                            <div class="clearfix"></div>
                                        </li>
                                    <?php } ?>
                                <?php } ?>
                            </div>
                        <?php endif; ?>

                        <?php foreach ($log['classroom'] as $index => $class) { ?>
                            <?php if (!empty($class['validate']['identification'])) { ?>
                                <div class="itens-censo">
                                    <h5>Turma - <?php echo $class['info']['name']; ?></h5>
                                    <?php foreach ($class['validate']['identification'] as $eindex => $classerror) { ?>
                                        <li>
                                        <span style="width: 20px;text-align: center;color:white;font-weight: bold"
                                              class="glyphicons activity-icon">
                                            <i></i>
                                        </span>
                                            <span class="ellipsis">
                                                <?php echo str_replace("*", "", Classroom::model()->getAttributeLabel(key($classerror))) ?>
                                                    -
                                                    <?php echo current($classerror) ?>
                                                <?php echo CHtml::link('- Corrigir', array('classroom/update',
                                                    'id' => $class['info']['id'], 'censo' => 1)); ?>
                                            </span>
                                            <?php $dataValidation['classroom' . $class['info']['id']][] = current($classerror); ?>
                                            <div class="clearfix"></div>
                                        </li>
                                    <?php } ?>
                                </div>
                            <?php } ?>

                        <?php } ?>

                        <?php foreach ($log['instructor'] as $index => $instructor) { ?>
                            <div class="itens-censo">
                                <h5>Professor - <?php echo $instructor['info']['name']; ?></h5>
                                <?php foreach ($instructor['validate']['identification'] as $instructorerror) { ?>
                                    <li>
                                    <span style="width: 20px;text-align: center;color:white;font-weight: bold"
                                          class="glyphicons activity-icon">
                                        <i></i>
                                    </span>
                                        <span class="ellipsis">
                                            <?php echo str_replace("*", "", InstructorIdentification::model()->getAttributeLabel(key($instructorerror))) ?>
                                                - <?php echo current($instructorerror) ?>
                                            <?php echo CHtml::link('- Corrigir', array('instructor/update',
                                                'id' => $instructor['info']['id'], 'censo' => 1)); ?>
                                        </span>
                                        <?php $dataValidation['instructor' . $instructor['info']['id']][] = current($instructorerror); ?>
                                        <div class="clearfix"></div>
                                    </li>
                                <?php } ?>
                                <?php foreach ($instructor['validate']['documents'] as $instructorerror) { ?>
                                    <li>
                                    <span style="width: 20px;text-align: center;color:white;font-weight: bold"
                                          class="glyphicons activity-icon">
                                        <i></i>
                                    </span>
                                        <span class="ellipsis">
                                            <?php echo str_replace("*", "", InstructorDocumentsAndAddress::model()->getAttributeLabel(key($instructorerror))) ?>
                                                - <?php echo current($instructorerror) ?>
                                            <?php echo CHtml::link('- Corrigir', array('instructor/update',
                                                'id' => $instructor['info']['id'], 'censo' => 1)); ?>
                                        </span>
                                        <?php $dataValidation['instructor' . $instructor['info']['id']][] = current($instructorerror); ?>
                                        <div class="clearfix"></div>
                                    </li>
                                <?php } ?>
                                <?php foreach ($instructor['validate']['variabledata'] as $variabledata) { ?>
                                    <?php foreach ($variabledata['errors'] as $vberros) { ?>
                                        <li>
                                    <span style="width: 20px;text-align: center;color:white;font-weight: bold"
                                          class="glyphicons activity-icon">
                                        <i></i>
                                    </span>
                                            <span class="ellipsis">Na turma <?php echo $variabledata['turma'] ?> |
                                                    <?php echo str_replace("*", "", InstructorVariableData::model()->getAttributeLabel(key($vberros))) ?>
                                                    - <?php echo current($vberros) ?>
                                                <?php echo CHtml::link('- Corrigir', array('classroom/update',
                                                    'id' => $variabledata['id'], 'censo' => 1)); ?>
                                        </span>
                                            <?php $dataValidation['classroom' . $variabledata['id']][] = current($vberros); ?>
                                            <div class="clearfix"></div>
                                        </li>
                                    <?php } ?>
                                <?php } ?>
                            </div>
                        <?php } ?>
                        <?php foreach ($log['student'] as $student) { ?>
                            <div class="itens-censo">
                                <h5>Aluno - <?php echo $student['info']['name']; ?></h5>
                                <?php foreach ($student['validate']['identification'] as $studenterror) { ?>
                                    <li>
                                    <span style="width: 20px;text-align: center;color:white;font-weight: bold"
                                          class="glyphicons activity-icon">
                                        <i></i>
                                    </span>
                                        <span class="ellipsis">
                                            <?php echo str_replace("*", "", StudentIdentification::model()->getAttributeLabel(key($studenterror))) ?>
                                                -
                                                <?php echo current($studenterror) ?>
                                            <?php echo CHtml::link('- Corrigir', array('student/update',
                                                'id' => $student['info']['id'], 'censo' => 1)); ?>
                                        </span>
                                        <?php $dataValidation['student' . $student['info']['id']][] = current($studenterror); ?>
                                        <div class="clearfix"></div>
                                    </li>
                                <?php } ?>
                                <?php foreach ($student['validate']['documents'] as $studenterror) { ?>
                                    <li>
                                    <span style="width: 20px;text-align: center;color:white;font-weight: bold"
                                          class="glyphicons activity-icon">
                                        <i></i>
                                    </span>
                                        <span class="ellipsis">
                                            <?php echo str_replace("*", "", StudentDocumentsAndAddress::model()->getAttributeLabel(key($studenterror))) ?>
                                                - <?php echo current($studenterror) ?>
                                            <?php echo CHtml::link('- Corrigir', array('student/update',
                                                'id' => $student['info']['id'], 'censo' => 1)); ?>
                                        </span>
                                        <?php $dataValidation['student' . $student['info']['id']][] = current($studenterror); ?>
                                        <div class="clearfix"></div>
                                    </li>
                                <?php } ?>
                                <?php foreach ($student['validate']['enrollment'] as $enrollment) { ?>
                                    <?php foreach ($enrollment['errors'] as $eberros) { ?>
                                        <li>
                                    <span style="width: 20px;text-align: center;color:white;font-weight: bold"
                                          class="glyphicons activity-icon">
                                        <i></i>
                                    </span>
                                            <span class="ellipsis">Na turma <?php echo $enrollment['turma'] ?> |
                                                    <?php echo str_replace("*", "", StudentEnrollment::model()->getAttributeLabel(key($eberros))) ?>
                                                    - <?php echo current($eberros) ?>
                                                <?php echo CHtml::link('- Corrigir', array('enrollment/update',
                                                    'id' => $enrollment['id'], 'censo' => 1)); ?>
                                            </span>
                                            <?php $dataValidation['enrollment' . $enrollment['id']][] = current($eberros); ?>
                                            <div class="clearfix"></div>
                                        </li>
                                    <?php } ?>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </ul>
                </div>
                <!-- // Filter Users Tab END -->
                <?php
                /*foreach ($dataValidation as $key => $value) {
                    Yii::app()->cache->set($key, $value, $timeExpiration);
                }*/
                ?>


            </div>
        </div>
        <script type="text/javascript">
            $(function () {
                $(".itens-censo li").parents('div').css("display", "block");
            });
            if ($(".list-timeline").find(".ellipsis").length) {
                $(".list-timeline").find(".alert").addClass("alert-error").removeClass("alert-success").find("strong").html("Foram encontradas pendências para exportação de dados para o EDUCACENSO. Corrija primeiro para exportar o arquivo.");
            } else {
                $(".list-timeline").find(".alert").addClass("alert-success").removeClass("alert-error").find("strong").html("Nenhuma pendência registrada.");

            }
        </script>
    </div>

</div>
