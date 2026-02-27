<?php
/** @var Notification[] $notifications */
$this->pageTitle = 'Notificações';
?>

<div class="main">
    <div class="row align-items--center justify-content--space-between">
        <div class="column">
            <h1>
                <i class="fa fa-bell"></i> Notificações Enviadas
            </h1>
        </div>
        <div class="column no-grow">
            <a href="<?= Yii::app()->createUrl('notifications/notifications/create') ?>"
               class="t-button-primary">
                <i class="fa fa-plus"></i> Nova Notificação
            </a>
        </div>
    </div>

    <?php if (Yii::app()->user->hasFlash('success')): ?>
        <div class="t-badge-success t-padding-small--all t-margin-small--y">
            <i class="fa fa-check-circle"></i> <?= Yii::app()->user->getFlash('success') ?>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="column">
            <table class="tag-table-primary" id="notifications-table">
                <thead>
                    <tr>
                        <th style="width: 40px">Tipo</th>
                        <th>Título</th>
                        <th style="width: 100px">Origem</th>
                        <th style="width: 120px">Escola</th>
                        <th style="width: 80px">Destinos</th>
                        <th style="width: 140px">Criada em</th>
                        <th style="width: 140px">Expira em</th>
                        <th style="width: 100px">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($notifications)): ?>
                        <tr>
                            <td colspan="8" class="text-align--center t-padding-large--all text-color--ink">
                                Nenhuma notificação enviada ainda.
                            </td>
                        </tr>
                    <?php endif; ?>
                    <?php foreach ($notifications as $n): ?>
                        <tr>
                            <td class="text-align--center">
                                <?php
                                $typeIcons = [
                                    'info' => '<i class="fa fa-info-circle text-color--info" style="color:#3498db"></i>',
                                    'warning' => '<i class="fa fa-exclamation-triangle" style="color:#f39c12"></i>',
                                    'error' => '<i class="fa fa-times-circle" style="color:#e74c3c"></i>',
                                    'success' => '<i class="fa fa-check-circle" style="color:#2ecc71"></i>',
                                ];
                                echo $typeIcons[$n->type] ?? $typeIcons['info'];
                                ?>
                            </td>
                            <td>
                                <a href="<?= Yii::app()->createUrl('notifications/notifications/view', ['id' => $n->id]) ?>" class="text-bold">
                                    <?= CHtml::encode($n->title) ?>
                                </a>
                            </td>
                            <td><?= $n->source === 'admin' ? 'Admin' : 'Sistema' ?></td>
                            <td><?= $n->school ? CHtml::encode($n->school->name) : 'Todas' ?></td>
                            <td class="text-align--center"><?= count($n->recipients) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($n->created_at)) ?></td>
                            <td>
                                <?= $n->expires_at
                                    ? date('d/m/Y H:i', strtotime($n->expires_at))
                                    : '<span class="text-color--ink">Nunca</span>' ?>
                            </td>
                            <td class="align-items--center">
                                <a href="<?= Yii::app()->createUrl('notifications/notifications/view', ['id' => $n->id]) ?>"
                                   class="t-button-icon secondary nofloat" title="Ver" style="padding: 4px 8px; margin: 0 4px;">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <a href="<?= Yii::app()->createUrl('notifications/notifications/delete', ['id' => $n->id]) ?>"
                                   class="t-button-icon secondary nofloat text-color--red"
                                   title="Excluir"
                                   style="padding: 4px 8px; margin: 0 4px;"
                                   onclick="return confirm('Excluir esta notificação?')">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
