<div class="widget-body">
    <?php $this->widget('zii.widgets.grid.CGridView', array(
        'dataProvider' => $dataProvider,
        'enablePagination' => true,
        'enableSorting' => false,
        'itemsCssClass' => 'js-tag-table tag-table-primary table table-condensed table-striped table-hover table-primary table-vertical-center checkboxs',
        'columns' => array(
            array(
                'header' => Yii::t('default', 'Name'),
                'name' => 'name',
                'type' => 'raw',
                'value' => 'CHtml::link($data->name,Yii::app()->createUrl("courseplan/courseplan/validatePlan",array("id"=>$data->id)))',
                'htmlOptions' => array('width' => '25%', 'class' => 'link-update-grid-view')
            ),
            array(
                'header' => Yii::t('default', 'Autor'),
                'name' => 'users_fk',
                'value' => '$data->usersFk->name',
                'htmlOptions' => array('width' => '25%'),
                'filter' => false
            ),
            array(
                'header' => Yii::t('default', 'Situation'),
                'name' => 'situation',
                'value' => '$data->situation',
                'htmlOptions' => array('width' => '25%'),
            ),
            array(
                'header' => 'Ações',
                'class' => 'CButtonColumn',
                'template' => '{validate}',
                'buttons' => array(
                    'validate' => array(
                        'label' => 'Validar',
                        'url' => 'Yii::app()->createUrl("courseplan/courseplan/validatePlan",array("id"=>$data->id))',
                        'imageUrl' => Yii::app()->theme->baseUrl.'/img/activeUser.svg'
                    ),
                ),
                'afterDelete' => 'function(link, success, data){
                    data = JSON.parse(data);
                    if (data.valid) {
                        $(".alert").text(data.message).addClass("alert-success").removeClass("alert-error");
                    } else {
                        $(".alert").text(data.message).addClass("alert-error").removeClass("alert-success");
                    }
                    $(".courseplan-alert").show();
                }',
            )
        )
    )); ?>
</div>
