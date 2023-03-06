<?php
	/* @var $this AdminController */

	$baseUrl = Yii::app()->baseUrl;
	$cs = Yii::app()->getClientScript();
	$cs->registerScriptFile($baseUrl . '/js/admin/index/global.js', CClientScript::POS_END);

	$this->pageTitle = 'TAG - ' . Yii::t('default', 'Conflicts');

	$form = $this->beginWidget('CActiveForm', [
		'id' => 'conflicts', 'enableAjaxValidation' => FALSE,
	]);

?>

	<div class="row-fluid">
		<div class="span12">
			<h1><?php echo Yii::t('default', 'Conflicts'); ?></h1>
		</div>
	</div>

	<div class="innerLR">

		<div class="widget widget-tabs border-bottom-none">

			<div class="widget-head">
				<ul class="tab-school">
					<li id="tab-instructors" class="active"><a class="glyphicons nameplate" href="#instructors"
					                                           data-toggle="tab"><i></i><?php echo Yii::t('default', 'Instructors') ?>
						</a></li>
					<li id="tab-students"><a class="glyphicons parents" href="#students"
					                         data-toggle="tab"><i></i><?php echo Yii::t('default', 'Students') ?></a>
					</li>
				</ul>
			</div>

			<div class="widget-body form-horizontal">
				<div class="tab-content">
					<!-- Tab content -->
					<?php foreach ($conflicts as $category => $data) : ?>
						<div class="tab-pane <?=$category == "instructors" ? "active" : ""?>" id="<?=$category?>">
							<?php foreach ($data as $conflict): ?>
								<div class="conflict-container">
									<div class="conflict-1">
										<div class="conflict-title">
											<i class="collapse-icon fa fa-plus-square"></i> <?= $conflict[0]->name ?>
										</div>
										<div class="conflict-values">
											<?php
												$diff = [];
												$attributes0 = $conflict[0]->attributes;
												$attributes1 = $conflict[1]->attributes;
												foreach ($attributes0 as $key => $value) {
													if ($value != $attributes1[$key]) {
														$diff[$key] = $attributes1[$key];
													}
												}
												foreach ($attributes0 as $key => $value) :
													if (isset($diff[$key])) :
														?>
														<p class="conflict-value">
															<strong><?= $conflict[0]->getAttributeLabel($key) ?>
																:</strong> <?= $value ?>
														</p>
													<?php endif; endforeach; ?>
										</div>
									</div>
									<div class="conflict-2">
										<div class="conflict-title">
											<?= $conflict[1]->name ?>
										</div>
										<div class="conflict-values">
											<?php
												foreach ($attributes1 as $key => $value) :
													if (isset($diff[$key])) :
														?>
														<p class="conflict-value">
															<strong><?= $conflict[1]->getAttributeLabel($key) ?>
																:</strong> <?= $value ?>
														</p>
													<?php endif; endforeach; ?>
										</div>
									</div>
									<div style="clear: both"></div>
								</div>
							<?php endforeach; ?>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>

<?php $this->endWidget(); ?>