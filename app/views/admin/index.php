<?php
/* @var $this AdminController */

$this->pageTitle = Yii::app()->name . ' - Administração';
$this->breadcrumbs = array(
    'Administração',
);
?>

<div class="heading-buttons">
    <h3>Administração</h3>
</div>

<div class="innerLR home">
    <div class="row-fluid">
        <?php if (Yii::app()->user->hasFlash('success')): ?>
                <div class="alert alert-success">
                    <?php echo Yii::app()->user->getFlash('success') ?>
                </div>
                <br/>
            <?php endif ?>
        <div class="span6">
            <div class="row-fluid">
                <div class="span3">
                    <a href="<?php echo Yii::app()->homeUrl; ?>?r=admin/ACL" class="widget-stats">
                        <span class="glyphicons flag"><i></i></span>
                        <span class="txt">Config. Permissões (Em manutenção!)</span>
                        <div class="clearfix"></div>
                    </a>
                </div>
                <div class="span3">
                    <a href="<?php echo Yii::app()->homeUrl; ?>?r=admin/import" class="widget-stats">
                        <span class="glyphicons database_plus"><i></i></span>
                        <span class="txt">Importar dados</span>
                        <div class="clearfix"></div>
                    </a>
                </div>
                <div class="span3">
                    <a href="<?php echo Yii::app()->homeUrl; ?>?r=admin/clearDB" class="widget-stats">
                        <span class="glyphicons database_minus"><i></i></span>
                        <span class="txt">Limpar Banco</span>
                        <div class="clearfix"></div>
                    </a>
                </div>
                <div class="span3">
                    <a href="<?php echo Yii::app()->homeUrl; ?>?r=admin/createUser" class="widget-stats">
                        <span class="glyphicons user"><i></i></span>
                        <span class="txt">Cadastrar usuário</span>
                        <div class="clearfix"></div>
                    </a>
                </div>
            </div>
<!--            <div class="row-fluid">
                <div class="span10 offset2">
                    <img class="logo-img" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/tag_logo.png" alt="Logo TAG" />
                </div>
            </div> -->
        </div>
    </div>
</div>
    
