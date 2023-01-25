<?php
/* @var $this DefaultController */

$themeUrl = Yii::app()->theme->baseUrl;
$homeUrl = Yii::app()->controller->module->baseUrl;
$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;

?>

<div class="ui middle aligned center aligned grid ">
    <div class="column ">
        <div class="ui raised segments">
            <div class="ui tag-blue inverted segment">
                <h2 class="ui image header">
                    <img src="<?= $baseScriptUrl ?>/common/img/logo.png" class="image">

                    <div class="content">
                        Acesso ao Boletim Escolar
                    </div>
                </h2>
            </div>
            <div class="ui segment">
                <?php
                $form = $this->beginWidget('CActiveForm', [
                    'id' => 'LoginForm',
                    'enableAjaxValidation' => false,
                    'htmlOptions' => [
                        'class' => 'ui large form',
                    ],
                ]);
                ?>
                    <div class="field">
                        <div class="ui left icon input">
                            <i class="user icon"></i>
                            <input maxlength="11" type="text" name="LoginForm[username]" placeholder="CPF">
                        </div>
                    </div>
                    <div class="field">
                        <div class="ui left icon input">
                            <i class="lock icon"></i>
                            <input type="password" name="LoginForm[password]" placeholder="Senha">
                        </div>
                    </div>
                    <input type="submit" class="ui inverted fluid large tag-blue submit button" value="Acessar"/>
                    <div class="ui error message"><?= $form->errorSummary($model); ?></div>
                <?php $this->endWidget(); ?>
            </div>
        </div>
    </div>
</div>

