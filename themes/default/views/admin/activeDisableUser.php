<?php
/* @var $this AdminController */

$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/admin/index/dialogs.js', CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/js/admin/index/global.js', CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/js/admin/activeDisableUser/_initialization.js', CClientScript::POS_END);
$themeUrl = Yii::app()->theme->baseUrl;

?>

<div id="mainPage" class="main">
<?php
$this->setPageTitle('TAG - ' . Yii::t('default', 'Users'));
?>
    <div class="row-fluid hide-responsive" style="margin-bottom: 50px;">
        <div class="span12">
            <h1><?php echo Yii::t('default', 'Users') ?></h1> 
        </div>
    </div>
    <div class="widget">
        <div class="widget-body">
            <div class="grid-view">
                <table class="js-tag-table tag-table-primary table table-condensed table-striped table-hover table-primary table-vertical-center checkboxs">
                    <thead>
                        <tr>
                            <th scope="col">Nome</th>
                            <th scope="col">Usuário</th>
                            <th scope="col" style="text-align: center">Ativar/Desativar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user) {?>
                            <tr>
                                <td class="link-update-grid-view" tabindex="0">
                                    <a href="/?r=admin/update&id=<?=$user->id?>" style="cursor: pointer;"><?= $user->name?></a>
                                </td>
                                <td><?= $user->username?></td>
                                <td style="text-align:center;">
                                    <a href="/?r=admin/<?php echo $user->active ? "disable" : "active"?>User&id=<?=$user->id?>" style="cursor: pointer;">
                                        <img src="/themes/default/img/<?php echo $user->active ? "disable" : "active"?>User.svg" alt="Link">
                                    </a>
                                </td>
                            </tr>
                        <?php }?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
