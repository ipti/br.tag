<div id="mainPage" class="main">
    <?php
    $this->setPageTitle('TAG - ' . Yii::t('default', 'Users'));

    $contextDesc = Yii::t('default', 'Available actions that may be taken on Classroom.');
    $this->menu = array(
        array('label' => Yii::t('default', 'Users'), 'url' => array('create'), 'description' => Yii::t('default', 'This action create a new Classroom')),
    );
    ?>
    
    <div class="innerLR">
        <div class="columnone" style="padding-right: 1em">
            <?php if (Yii::app()->user->hasFlash('success')): ?>
                <div class="alert alert-success">
                    <?php echo Yii::app()->user->getFlash('success') ?>
                </div>
                <br/>
            <?php endif ?>
            <div class="widget">  
                <div class="widget-body">
                    <?php
                    $this->widget('zii.widgets.grid.CGridView', array(
                        'dataProvider' => $dataProvider,
                        'enablePagination' => false,
                        'enableSorting' => false,
                        'itemsCssClass' => 'js-tag-table tag-table table table-condensed table-striped table-hover table-primary table-vertical-center checkboxs',
                        'columns' => array(
                            array(
                                'name' => 'name',
                                'type' => 'raw',
                                'value' => 'CHtml::link($data->name,Yii::app()->createUrl("admin/update",array("id"=>$data->id)))',
                                'htmlOptions' => array('width' => '400px')
                            ),
                            array(
                                'name' => 'username',
                                'type' => 'raw',
                                'value' => '$data->username',
                            ),
                            array(
                                'name' => 'active',
                                'type' => 'raw',
                                'value' => '$data->active ? Sim : Não'
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
                        ),
                    ));
                    ?>
                </div>   
            </div>
        </div>
    </div>

</div>
