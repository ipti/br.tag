<div class="widget-body">
    <?php $this->widget('zii.widgets.grid.CGridView', [
        'dataProvider' => $dataProvider,
        'enablePagination' => true,
        'enableSorting' => false,
        'itemsCssClass' => 'js-tag-table tag-table-primary table table-condensed table-striped table-hover table-primary table-vertical-center checkboxs',
        'columns' => [
            [
                'header' => Yii::t('default', 'Name'),
                'name' => 'name',
                'type' => 'raw',
                'value' => 'CHtml::link($data->name,Yii::app()->createUrl("courseplan/courseplan/validatePlan",array("id"=>$data->id)))',
                'htmlOptions' => ['width' => '25%', 'class' => 'link-update-grid-view']
            ],
            [
                'header' => Yii::t('default', 'Autor'),
                'name' => 'users_fk',
                'value' => '$data->usersFk->name',
                'htmlOptions' => ['width' => '25%'],
                'filter' => false
            ],
            [
                'header' => Yii::t('default', 'Situation'),
                'name' => 'situation',
                'value' => '$data->situation',
                'htmlOptions' => ['width' => '25%'],
            ],
            [
                'header' => 'Ações',
                'class' => 'CButtonColumn',
                'template' => '{validate}',
                'buttons' => [
                    'validate' => [
                        'label' => 'Validar',
                        'url' => 'Yii::app()->createUrl("courseplan/courseplan/validatePlan",array("id"=>$data->id))',
                        'imageUrl' => Yii::app()->theme->baseUrl . '/img/activeUser.svg'
                    ],
                ],
                'afterDelete' => 'function(link, success, data){
                    data = JSON.parse(data);
                    if (data.valid) {
                        $(".alert").text(data.message).addClass("alert-success").removeClass("alert-error");
                    } else {
                        $(".alert").text(data.message).addClass("alert-error").removeClass("alert-success");
                    }
                    $(".courseplan-alert").show();
                }',
            ]
        ]
    ]); ?>
</div>
