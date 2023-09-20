<?php

/**
 * Created by PhpStorm.
 * User: IPTIPC100
 * Date: 29/06/2016
 * Time: 14:02
 */
$baseUrl = Yii::app()->baseUrl;
$themeUrl = Yii::app()->theme->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/instructor/form/updateEmails.js', CClientScript::POS_END);

$this->setPageTitle('TAG - ' . Yii::t('default', 'Update Instructor e-mails'));

$form = $this->beginWidget('CActiveForm', [
    'id' => 'updateEmails-form', 'enableAjaxValidation' => FALSE,
]);
?>
<div class="main">
    <div class="row-fluid">
        <div class="mobile-row justify-content--space-between">
            <h1><?= yii::t('default', 'Update Instructor e-mails') ?></h1>
            <?php echo CHtml::htmlButton(Yii::t('default', 'Save'), array('id' => 'save-emails', 'class' => 't-button-primary  last saveDesktop show--tabletDesktop', 'type' => 'button')); ?>
        </div>
    </div>
    <div class="tag-inner instructor-emails">
        <div class="alert alert-danger">Preencha os e-mails corretamente.</div>
        <div class="row wrap">
            <?php $half_count = ceil(count($instructors) / 2); ?>
            <div class="column is-two-fifths t-field-text">
                <?php foreach (array_slice($instructors, 0, $half_count) as $instructor) : ?>
                    <div class="t-field-text">
                        <label class="t-field-text__label" for="instructor"><?= $instructor->name ?></label>
                        <input name="<?= $instructor->id ?>" class="t-field-text__input" type="email">
                        <!-- style="white-space:nowrap; overflow-y: hidden; overflow-x: auto; text-overflow:ellipsis; margin-top: 5px;" -->
                    </div>
                <?php endforeach ?>
            </div>
            <div class="column is-two-fifths t-field-text">
                <?php foreach (array_slice($instructors, $half_count) as $instructor) : ?>
                    <div class="t-field-text">
                        <label class="t-field-text__label" for="instructor"><?= $instructor->name ?></label>
                        <input name="<?= $instructor->id ?>" class="t-field-text__input" type="email">
                    </div>
                <?php endforeach ?>
            </div>
        </div>
        <div class="clear"></div>
    </div>
    <div class="row reverse show--mobile">
        <div class=" justify-content--center">
            <button type="button" id="save-emails" class="t-button-primary save">Salvar</button>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>
