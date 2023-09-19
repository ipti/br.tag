<?php
/* @var $form CActiveForm */
?>


<?php

$baseUrl = Yii::app()->baseUrl;
$themeUrl = Yii::app()->theme->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerCssFile($baseUrl . 'css/admin.css');
$cs->registerScriptFile($baseUrl . '/js/admin/auditory.js', CClientScript::POS_END);

$this->setPageTitle('TAG - Auditoria');
?>
<?php //echo $form->errorSummary($model);
?>

<div class="main">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'classes-form',
        'enableAjaxValidation' => false,
    ));
    ?>
    <div class="row-fluid">
        <div class="span12">
            <h1>Auditoria</h1>
        </div>
    </div>
    <div class="tag-inner">
        <?php if (Yii::app()->user->hasFlash('success')) : ?>
            <div class="alert alert-success">
                <?php echo Yii::app()->user->getFlash('success') ?>
            </div>
        <?php endif ?>
        <div class="alert-required-fields no-show alert alert-error">Preencha os campos de pesquisa corretamente.</div>
        <input type="hidden" class="school-year" value="<?= $schoolyear ?>">
        <div class="form-control control-group">
            <!-- Escola, ação (create, update, delete), usuário, data -->
            <div>
                <label class="control-label required">Escola</label>
                <div>
                    <?php
                    echo $form->dropDownList(new SchoolIdentification(), 'inep_id', CHtml::listData($schools, 'inep_id', 'name'), array(
                        'key' => "inep_id",
                        'id' => "log-school",
                        'class' => 'select-search-on control-input schools',
                        'prompt' => 'Selecione...',
                    ));
                    ?>
                </div>
            </div>
        </div>
        <div class="form-control control-group">
            <div>
                <label class="control-label required">Ação</label>
                <div>
                    <select id="log-action" class="select-search-on">
                        <option value="">Selecione...</option>
                        <option value="C">Criar</option>
                        <option value="U">Editar</option>
                        <option value="D">Remover</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="form-control control-group">
            <div>
                <label class="control-label required">Usuário</label>
                <div>
                    <?php
                    echo $form->dropDownList(new Users(), 'id', CHtml::listData($users, 'id', 'name'), array(
                        'key' => "id",
                        'id' => "log-user",
                        'class' => 'select-search-on control-input users',
                        'prompt' => 'Selecione...',
                    ));
                    ?>
                </div>
            </div>
        </div>
        <div class="form-control control-group">
            <div>
                <label class="control-label required">Período <span class="red">*</span></label>
                <div>
                    <input type="text" class="form-control date auditory-initial-date" name="initial-date"
                           placeholder="Inicial">
                    <input type="text" class="form-control date auditory-final-date" name="final-date"
                           placeholder="Final">
                </div>
            </div>
        </div>
        <div>
            <div class="auditory-button-container">
                <a id="loadtable"
                   class='t-button-primary'><?php echo Yii::t('default', 'Search') ?>
                </a>
            </div>
            <img class="loading-auditory" style="display:none;margin: 10px 20px;" height="30px" width="30px"
                 src="<?php echo Yii::app()->theme->baseUrl; ?>/img/loadingTag.gif" alt="TAG Loading">
        </div>
        <div class="auditory-table-container table-responsive">
            <table class="auditory-table table" aria-labelledby="auditory list">
                <thead>
                <tr>
                    <th colspan="5" class="table-title"  scope="col">Auditoria</th>
                </tr>
                <tr>
                    <th scope="col">Escola</th>
                    <th scope="col">Autor</th>
                    <th scope="col">Ação</th>
                    <th scope="col">Evento</th>
                    <th scope="col">Data</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>
</div>