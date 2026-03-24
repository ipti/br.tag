<div class="tag-inner">
    <div class="widget clearmargin">
        <div class="widget-body">
            <?php
            $this->widget('zii.widgets.grid.CGridView', [
                'dataProvider' => $dataProvider,
                'enablePagination' => false,
                'enableSorting' => false,
                'ajaxUpdate' => false,
                'itemsCssClass' => 'js-tag-table tag-table-primary table table-condensed table-striped table-hover table-primary table-vertical-center checkboxs',
                'columns' => [
                    [
                        'name' => 'id',
                        'type' => 'raw',
                        'value' => '$data->id',
                    ],
                    [
                        'name' => 'name',
                        'type' => 'raw',
                        'value' => 'CHtml::link($data->name,Yii::app()->createUrl("studentimc/studentimc/index",array("studentId"=>$data->id)))',
                        'htmlOptions' => ['width' => '400px', 'class' => 'link-update-grid-view'],
                    ],
                    [
                        'name' => 'filiation_1',
                        'header' => 'Filição principal',
                        'value' => '$data->filiation_1',
                        'htmlOptions' => ['width' => '400px']
                    ],
                    [
                        'name' => 'documents',
                        'header' => 'CPF',
                        'value' => '$data->documentsFk->cpf',
                        'htmlOptions' => ['width' => '400px']
                    ],
                    [
                        'name' => 'birthday_date',
                        'header' => 'Data de Nascimento',
                        'filter' => false,
                        'value' => '$data->birthday',
                    ],
                ],
            ]);
            ?>
        </div>
    </div>
</div>