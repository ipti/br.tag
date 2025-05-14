<?php
/* @var $this FoodMenuController */
/* @var $model FoodMenu */

$this->setPageTitle('TAG - ' . Yii::t('default', 'Cardápios'));

$this->breadcrumbs = array(
	'Food Menus' => array('index'),
	'Manage',
);

$this->menu = array(
	array('label' => 'List FoodMenu', 'url' => array('index')),
	array('label' => 'Create FoodMenu', 'url' => array('create')),
);

?>
<div id="mainPage" class="main container-instructor">

	<div class="row-fluid">
		<div class="span12">
			<h1>
				Habilidades
			</h1>
			<div class="t-buttons-container">
                <?php
                    if(Yii::app()->getAuthManager()->checkAccess('manager',Yii::app()->user->loginInfos->id) || Yii::app()->getAuthManager()->checkAccess('nutritionist',Yii::app()->user->loginInfos->id) ||  Yii::app()->getAuthManager()->checkAccess('admin',Yii::app()->user->loginInfos->id)):
                ?>
                    <a class="t-button-primary"  rel="noopener" href="<?= Yii::app()->createUrl('foods/foodMenu/create') ?>">
                        <?= Yii::t('default', 'Add') ?>
                    </a>
                <?php
                    endif;
                ?>
				<a class="t-button-secondary" target="_blank"  rel="noopener"
					href="<?php echo Yii::app()->createUrl('foods/reports/ShoppingListReport') ?>">
					<span class="t-icon-printer"></span>Lista de Compras
				</a>
			</div>
		</div>
	</div>


	<div class="tag-inner">
		<?php if (Yii::app()->user->hasFlash('success')): ?>
			<div class="alert alert-success">
				<?php echo Yii::app()->user->getFlash('success') ?>
			</div>
			<br />
		<?php endif ?>
		<div class="widget clearmargin">
			<div class="widget-body">

				<?php

                $this->widget(
					'zii.widgets.grid.CGridView',
					array(
						'id' => 'food-menu-grid',
						'dataProvider' => $dataProvider,
						'enablePagination' => false,
						'enableSorting' => false,
						'ajaxUpdate' => false,
						'itemsCssClass' => 'js-tag-table tag-table-primary table table-condensed table-striped table-hover table-primary table-vertical-center checkboxs',
						'columns' => array(
							'id',
							'description' => array(
								'header' => 'Nome',
								'type' => 'raw',
								'value' => function($data) {

                                    if (Yii::app()->getAuthManager()->checkAccess('nutritionist',Yii::app()->user->loginInfos->id) ||  Yii::app()->getAuthManager()->checkAccess('admin',Yii::app()->user->loginInfos->id))
                                    {
                                        return CHtml::link($data->description, Yii::app()->createUrl("foods/foodmenu/update",array("id"=>$data->id)));
                                    } else {
                                        return CHtml::link($data->description, Yii::app()->createUrl('foods/reports/FoodMenuReport', array('id'=>$data->id)));
                                    }
                                },
								'htmlOptions' => array('width' => '400px', 'class' => 'link-update-grid-view'),
							),
							'start_date' => array(
								'header' => 'Início',
								'type' => 'raw',
								'value' => 'DateTime::createFromFormat("Y-m-d",$data->start_date)->format("d/m/Y")',
								'htmlOptions' => array('width' => '400px', 'class' => 'link-update-grid-view'),
							),
							'final_date' => array(
								'header' => 'Fim',
								'type' => 'raw',
								'value' => 'DateTime::createFromFormat("Y-m-d",$data->final_date)->format("d/m/Y")',
								'htmlOptions' => array('width' => '400px', 'class' => 'link-update-grid-view'),
							),
							array(
								'header' => 'Ações',
								'class' => 'CButtonColumn',
								'template' => '{update}{delete}{report}',
								'buttons' => array(
									'update' => array(
										'imageUrl' => Yii::app()->theme->baseUrl . '/img/editar.svg',
                                        'visible' => "Yii::app()->getAuthManager()->checkAccess('nutritionist',Yii::app()->user->loginInfos->id) ||  Yii::app()->getAuthManager()->checkAccess('admin',Yii::app()->user->loginInfos->id)",
									),
									'delete' => array(
										'imageUrl' => Yii::app()->theme->baseUrl . '/img/deletar.svg',
                                        'visible' => "Yii::app()->getAuthManager()->checkAccess('nutritionist',Yii::app()->user->loginInfos->id) ||  Yii::app()->getAuthManager()->checkAccess('admin',Yii::app()->user->loginInfos->id)",
                                    ),
                                    'report' => array(
                                        'label' => '<span class="t-icon-weather-report"><span>',  // Adiciona um rótulo para o botão
                                        'url' => 'Yii::app()->createUrl("foods/reports/FoodMenuReport", array("id" => $data->id))',
                                        'visible' => '(!Yii::app()->getAuthManager()->checkAccess("nutritionist", Yii::app()->user->loginInfos->id) && !Yii::app()->getAuthManager()->checkAccess("admin", Yii::app()->user->loginInfos->id))',
                                    )
								),
								'updateButtonOptions' => array('style' => 'margin-right: 20px;'),
								'deleteButtonOptions' => array('style' => 'cursor: pointer;'),
								'htmlOptions' => array('width' => '100px', 'style' => 'text-align: center'),
							)
						),
					)
				); ?>

			</div>
		</div>
	</div>
</div>
