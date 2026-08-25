<?php
/* @var $this FoodalertController */
/* @var $groups FoodExpirationAlertGroup[] */
/* @var $defaultDays int|null */

$this->setPageTitle('TAG - ' . Yii::t('default', 'Alerta de Vencimento'));
?>

<div id="mainPage" class="main">
    <div class="mobile-row">
        <div class="column clearleft">
            <h1 class="clear-padding--bottom">Alerta de Vencimento do Estoque</h1>
        </div>
        <div class="column clearfix align-items--center justify-content--end show--desktop">
            <a class="t-button-secondary" href="<?php echo Yii::app()->createUrl('foods/foodinventory'); ?>">
                Voltar para o Estoque
            </a>
        </div>
    </div>

    <?php if (Yii::app()->user->hasFlash('success')): ?>
        <div class="alert alert-success"><?php echo Yii::app()->user->getFlash('success'); ?></div>
    <?php endif; ?>
    <?php if (Yii::app()->user->hasFlash('error')): ?>
        <div class="alert alert-error"><?php echo Yii::app()->user->getFlash('error'); ?></div>
    <?php endif; ?>

    <div class="tag-inner">
        <div class="row">
            <div class="column clearleft">
                <h2>Prazo padrão</h2>
                <div class="t-badge-info t-margin-none--left">
                    <span class="t-info_positive t-badge-info__icon"></span>
                    Vale para todo alimento que não estiver em nenhum grupo abaixo.
                </div>
            </div>
        </div>
        <form method="post" action="<?php echo Yii::app()->createUrl('foods/foodalert/saveDefaultDays'); ?>" class="mobile-row align-items--end">
            <div class="column t-field-text clearleft is-one-fifth">
                <label class="t-field-text__label" for="defaultDays">Dias de antecedência</label>
                <input type="number" min="1" id="defaultDays" name="defaultDays" class="t-field-text__input" placeholder="Não configurado" value="<?php echo $defaultDays !== null ? (int) $defaultDays : ''; ?>">
            </div>
            <div class="column t-buttons-container">
                <button type="submit" class="t-button-primary">Salvar prazo padrão</button>
            </div>
        </form>
    </div>

    <div class="tag-inner">
        <div class="row">
            <div class="column clearfix">
                <h2>Grupos com prazo próprio</h2>
            </div>
            <div class="column clearfix justify-content--end show--desktop">
                <a class="t-button-primary" href="<?php echo Yii::app()->createUrl('foods/foodalert/create'); ?>">
                    Novo grupo
                </a>
            </div>
        </div>
        <div class="widget clearmargin">
            <div class="widget-body">
                <?php if (empty($groups)): ?>
                    <p>Nenhum grupo cadastrado. Todo alimento usa o prazo padrão.</p>
                <?php else: ?>
                    <div class="grid-view">
                        <table class="js-tag-table tag-table-primary tag-table table table-condensed table-striped table-hover table-primary table-vertical-center" aria-describedby="Grupos de alerta de vencimento">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Dias de antecedência</th>
                                    <th>Alimentos</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($groups as $group): ?>
                                    <tr>
                                        <td><?php echo CHtml::encode($group->name); ?></td>
                                        <td><?php echo (int) $group->alert_days; ?></td>
                                        <td><?php echo count($group->foods); ?></td>
                                        <td>
                                            <a href="<?php echo Yii::app()->createUrl('foods/foodalert/update', ['id' => $group->id]); ?>">
                                                <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/editar.svg" alt="Editar">
                                            </a>
                                            <?php echo CHtml::form(['foods/foodalert/delete', 'id' => $group->id], 'post', ['style' => 'display:inline-block; margin-left:12px;']); ?>
                                                <button type="submit" style="border:none; background:none; padding:0; cursor:pointer;" onclick="return confirm('Excluir o grupo &quot;<?php echo CHtml::encode($group->name); ?>&quot;? Os alimentos voltam a usar o prazo padrão.');">
                                                    <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/deletar.svg" alt="Excluir">
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
