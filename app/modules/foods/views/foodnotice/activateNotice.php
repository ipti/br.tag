<?php
$this->breadcrumbs=array(
	'Food Notice',
);

$baseUrl = Yii::app()->baseUrl;
$themeUrl = Yii::app()->theme->baseUrl;
$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseScriptUrl . '/notice/activateNotice.js', CClientScript::POS_END);

$this->setPageTitle('TAG - Editais');
$title = "Editais";
?>

<div id="mainPage" class="main">
    <div class="row">
        <h1>Editais</h1>
    </div>
    <div class="row">
        <div class="t-buttons-container">
            <a class="t-button-secondary" href="<?php echo Yii::app()->createUrl('foods/foodnotice/index')?>">Editais</a>
        </div>
    </div>

    <?php if (Yii::app()->user->hasFlash('success')): ?>
        <div class="alert alert-success">
            <?php echo Yii::app()->user->getFlash('success') ?>
        </div>
        <br/>
    <?php endif ?>
    <?php if (Yii::app()->user->hasFlash('error')): ?>
        <div class="alert alert-error">
            <?php echo Yii::app()->user->getFlash('error') ?>
        </div>
        <br/>
    <?php endif ?>

    <div class="widget-body">
        <div class="grid-view">
            <table class="js-tag-table tag-table-primary table table-condensed table-striped table-hover table-primary table-vertical-center checkboxs">
                <thead>
                    <tr>
                        <th scope="col">Nome</th>
                        <th scope="col">Ínicio</th>
                        <th scope="col">Status</th>
                        <th scope="col" style="text-align: center">Ativar/Inativar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($notices as $notice) {?>
                        <tr>
                            <td class="link-update-grid-view" tabindex="0">
                                <p style="cursor: pointer;"><?=$notice->name?></p>
                            </td>
                            <td><?=DateTime::createFromFormat("Y-m-d",$notice->date)->format("d/m/Y")?></td>
                            <td><?=$notice->status?></td>
                            <td style="text-align:center;">
                                <a id="js-change-notice-status" data-noticeId="<?= $notice->id?>" data-noticeStatus="<?= $notice->status?>" style="cursor: pointer;">
                                    <img src="/themes/default/img/<?php echo $notice->status == "Inativo" ? "disable" : "active"?>User.svg" alt="Link">
                                </a>
                            </td>
                        </tr>
                    <?php }?>
                </tbody>
            </table>
        </div>
    </div>
</div>
