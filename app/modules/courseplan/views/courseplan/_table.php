<div class="widget-body">
                    <?php $this->widget('zii.widgets.grid.CGridView', array(
                        'dataProvider' => $dataProvider,
                        'enablePagination' => false,
                        'enableSorting' => false,
                        'itemsCssClass' => 'js-tag-table tag-table-primary table table-condensed table-striped table-hover table-primary table-vertical-center checkboxs',
                        'columns' => array(
                            array(
                                'header' => Yii::t('default', 'Name'),
                                'name' => 'name',
                                'type' => 'raw',
                                'value' => 'CHtml::link($data->name,Yii::app()->createUrl("courseplan/courseplan/update",array("id"=>$data->id)))',
                                'htmlOptions' => array('width' => '25%', 'class' => 'link-update-grid-view')
                            ),
                            array(
                                'header' => Yii::t('default', 'Stage'),
                                'name' => 'modality_fk',
                                'type' => 'raw',
                                'value' => '$data->modalityFk->name',
                                'htmlOptions' => array('width' => '25%'),
                            ),
                            array(
                                'header' => Yii::t('default', 'Discipline'),
                                'name' => 'discipline_fk',
                                'value' => '$data->disciplineFk->name',
                                'htmlOptions' => array('width' => '20%'),
                                'filter' => false
                            ),
                            array(
                                'header' => Yii::t('default', 'Autor'),
                                'name' => 'users_fk',
                                'value' => '$data->usersFk->name',
                                'htmlOptions' => array('width' => '25%'),
                                'filter' => false
                            ),
                            array(
                                'header' => 'Ações',
                                'class' => 'CButtonColumn',
                                'template' => '{approved} {canceled} {deletePlan}',
                                'buttons' => array(
                                    'deletePlan' => array(
                                        'imageUrl' => Yii::app()->theme->baseUrl.'/img/deletar.svg',
                                        'url' => 'Yii::app()->createUrl("courseplan/courseplan/deletePlan",array("id"=>$data->id))',
                                    ),
                                    'approved' => array(
                                        'label' => '',
                                        'options' => [
                                            'class' => 't-course-plan-canceled',
                                            'title' => 'Desabilitado para Edição',
                                            'style' => 'font-size: 20px; padding-right: 3 3 0 0;'
                                        ],
                                        'url' => 'Yii::app()->createUrl("courseplan/courseplan/enableCoursePlanEdition",array("id"=>$data->id))',
                                        'visible' => '!Yii::app()->getAuthManager()->checkAccess("instructor", Yii::app()->user->loginInfos->id) && $data->situation !== CoursePlan::STATUS_PENNDING',
                                    ),
                                    'canceled' => array(
                                        'label' => '',
                                        'options' => [
                                            'class' => 't-course-plan-approved',
                                            'title' => 'Habilitado para Edição',
                                            'style' => 'font-size: 20px; padding: 3 3 0 0;'
                                        ],
                                        'url' => 'Yii::app()->createUrl("courseplan/courseplan/enableCoursePlanEdition",array("id"=>$data->id))',
                                        'visible' => '!Yii::app()->getAuthManager()->checkAccess("instructor", Yii::app()->user->loginInfos->id) && $data->situation === CoursePlan::STATUS_PENNDING',
                                    )
                                ),
                            ),
                        ),
                    )); ?>
</div>
