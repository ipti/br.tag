<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.13.2/r-2.4.0/datatables.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.13.2/r-2.4.0/datatables.min.js"></script>
<div id="mainPage" class="main">
    <?php
    $this->setPageTitle('TAG - ' . Yii::t('default', 'Student Identifications'));
    $contextDesc = Yii::t('default', 'Available actions that may be taken on StudentIdentification.');
    $this->menu = array(
        array('label' => Yii::t('default', 'Create a new StudentIdentification'), 'url' => array('create'), 'description' => Yii::t('default', 'This action create a new StudentIdentification')),
    );
    $themeUrl = Yii::app()->theme->baseUrl;
    $cs = Yii::app()->getClientScript();
    $cs->registerCssFile($themeUrl . '/css/template2.css');
    ?>

    <div class="row-fluid">
        <div class="span12 hide-responsive">
            <h3 class="heading-mosaic"><?php echo Yii::t('default', 'Student Identifications') ?></h3>  
            <div class="buttons span7">
                <!--<a href="<?= CHtml::normalizeUrl(array('student/create'))?>" class="btn btn-primary btn-icon glyphicons circle_plus"><i></i> Alunos PNE</a>-->
                <a href="<?= CHtml::normalizeUrl(array('wizard/configuration/student'))?>" class="tag-button medium-button">Matrícula em Grupo</a>
                <a href="<?= CHtml::normalizeUrl(array('student/create'))?>" class="tag-button medium-button"><?= Yii::t('default', 'Add') ?></a>
                <a href="<?= CHtml::normalizeUrl(array('student/create', 'simple' => 1))?>" class="tag-button medium-button"> <?= Yii::t('default', 'Add (Fast)') ?></a>
            </div>

        </div>
        <div class="btn-group pull-right responsive-menu dropdown-margin">
            <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                Menu
                <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
                <li><a href="<?= CHtml::normalizeUrl(array('wizard/configuration/student'))?>" class=""><i></i>Matrícula em Grupo</a></li>
                <li><a href="<?= CHtml::normalizeUrl(array('student/create'))?>" class=""><i></i> <?= Yii::t('default', 'Add') ?></a></li>
                <li><a href="<?= CHtml::normalizeUrl(array('student/create', 'simple' => 1))?>" class=""><i></i> <?= Yii::t('default', 'Add (Fast)') ?></a></li>
            </ul>
        </div>
    </div>

    <div class="tag-inner">
        <?php if (Yii::app()->user->hasFlash('error')): ?>
            <div class="alert alert-error">
                <?php echo Yii::app()->user->getFlash('error') ?>
            </div>
            <?php
            if (isset($buttons))
                echo $buttons;
            ?>
            <br/>
        <?php endif ?>
        <?php if (Yii::app()->user->hasFlash('success')): ?>
            <div class="alert alert-success">
                <?php echo Yii::app()->user->getFlash('success') ?>
            </div>
            <?php
            if (isset($buttons))
                echo $buttons;
            ?>
            <br/>
        <?php endif ?>
        <div class="widget">
            <div class="widget-body">
                <?php
                //<button type="submit" class="btn btn-icon btn-primary glyphicons circle_ok"><i>Ok</i></button>
                //@done S1 - 05 - Tirar borda esquerda e direita do filtro por nome dos alunos
                $this->widget('zii.widgets.grid.CGridView', array(
                    'dataProvider' => $dataProvider,
                    'enablePagination' => false,
                    'enableSorting' => false,
                    'selectableRows' => 1,
                    'itemsCssClass' => 'js-tag-table student-table tag-table table table-condensed table-striped table-hover table-primary table-vertical-center checkboxs',
                    'columns' => array(
                        array(
                            'name' => 'name',
                            'type' => 'raw',
                            'value' => '$data->name',
                        ),
                        /*array(
                            'header' => '',
                            'value' => '0+$data->documentsFk->received_cc+$data->documentsFk->received_address+$data->documentsFk->received_photo'
                            . '+$data->documentsFk->received_nis+$data->documentsFk->received_history'
                            . '+$data->documentsFk->received_responsable_rg+$data->documentsFk->received_responsable_cpf."/7"',
                            'htmlOptions' => array('width' => '5px')
                        ),*/
                        array(
                            'name' => 'filiation_1',
                            'htmlOptions' => array('width' => '400px')
                        ),
                        array(
                            'name' => 'birthday',
                            'filter' => false
                        ),
                        array(
                            'name' => 'inep_id'
                        ),
                        array(
                            'class' => 'CButtonColumn', 
                            'template' => '{update}',
                            'buttons' => array(
                                'update' => array(
                                    'imageUrl' => Yii::app()->theme->baseUrl.'/img/editar.svg',
                                )
                            )
                        ),
                        array(
                        'class' => 'CButtonColumn', 
                        'template' => '{delete}',
                        'buttons' => array(
                            'delete' => array(
                                'imageUrl' => Yii::app()->theme->baseUrl.'/img/deletar.svg',
                            )
                        )
                    ),
                    ),
                ));
                ?>
            </div>
        </div>
    </div>
    <div class="columntwo">
    </div>
</div>

</div>
