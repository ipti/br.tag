<?php
/* @var $this ProfessionalController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs = [
    'Professionals',
];

?>

<div id="mainPage" class="main container-professional">
    <?php
    $this->setPageTitle('TAG - ' . Yii::t('default', 'Professional Identifications'));
$contextDesc = Yii::t('default', 'Available actions that may be taken on professionalIdentification.');
$this->menu = [
    ['label' => Yii::t('default', 'Create a new professionalIdentification'), 'url' => ['create'], 'description' => Yii::t('default', 'This action create a new professionalIdentification')],
];
$themeUrl = Yii::app()->theme->baseUrl;
$cs = Yii::app()->getClientScript();

?>


    <div class="row-fluid box-professional">
        <div class="span12">
            <h1><?php echo Yii::t('default', 'Professional Identifications') ?></h1>
            <div class="t-buttons-container">
                <a class="t-button-primary" href="<?php echo Yii::app()->createUrl('professional/default/create')?>">Adicionar profissional</a>
            </div>
        </div>
        <div class="btn-group pull-right mt-30 responsive-menu dropdown-margin">
            <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                Menu
                <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
                <li><a href="<?php echo Yii::app()->createUrl('professional/default/create')?>"><i></i> Adicionar profissional</a></li>
            </ul>
        </div>
    </div>

    <div class="tag-inner">
        <?php if (Yii::app()->user->hasFlash('success')): ?>
            <div class="alert alert-success">
                <?php echo Yii::app()->user->getFlash('success') ?>
            </div>
            <br/>
        <?php endif ?>
        <?php if (Yii::app()->user->hasFlash('error')): ?>
            <div class="alert alert-error">
                <?php echo Yii::app()->user->getFlash('error') ?>
            </div>
            <br/>
        <?php endif ?>
        <div class="widget clearmargin">
            <div class="widget-body">
                <?php
                DataTableGridView::show($this, [
                    'id' => 'professional-grid',
                    'dataProvider' => $dataProvider,
                    'columns' => [
                        [
                            'name' => 'name',
                            'header' => 'Nome',
                            'type' => 'raw',
                            'value' => 'CHtml::link($data->name, Yii::app()->createUrl("professional/default/update", array("id" => $data->id_professional)))',
                            'htmlOptions' => ['class' => 'link-update-grid-view'],
                        ],
                        [
                            'name' => 'cpf_professional',
                            'header' => 'CPF',
                        ],
                        [
                            'name' => 'speciality',
                            'header' => 'Especialidade',
                        ],
                        [
                            'name' => 'fundeb',
                            'header' => 'Fundeb',
                            'value' => '$data->fundeb ? "Sim" : "Não"',
                        ],
                        [
                            'header' => 'Ações',
                            'class' => 'CButtonColumn',
                            'template' => '{update}{delete}',
                            'buttons' => [
                                'update' => [
                                    'imageUrl' => Yii::app()->theme->baseUrl . '/img/editar.svg',
                                ],
                                'delete' => [
                                    'imageUrl' => Yii::app()->theme->baseUrl . '/img/deletar.svg',
                                ]
                            ],
                            'updateButtonOptions' => ['style' => 'margin-right: 20px;'],
                            'deleteButtonOptions' => ['style' => 'cursor: pointer;'],
                            'htmlOptions' => ['width' => '100px', 'style' => 'text-align: center'],
                        ],
                    ],
                ]);
                ?>
            </div>
        </div>
    </div>
</div>

