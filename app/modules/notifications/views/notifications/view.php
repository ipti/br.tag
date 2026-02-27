<?php
/** @var Notification $notification */
$this->pageTitle = 'Notificação: ' . $notification->title;
?>

<div class="main">
    <div class="row">
        <div class="column is-two-thirds">
            <div class="t-buttons-container auto-width t-margin-small--bottom">
                <a href="<?= Yii::app()->createUrl('notifications/notifications/index') ?>"
                   class="t-button-secondary">
                    <i class="fa fa-arrow-left"></i> Voltar
                </a>
            </div>

            <div class="t-cards">
                <div class="t-cards-content">

                    <!-- Header: badge tipo + metadado -->
                    <div class="row justify-content--space-between align-items--center no-gap t-margin-small--bottom">
                        <div class="column no-grow">
                            <?php
                            $typeBadges = [
                                'info'    => 't-badge-info',
                                'warning' => 't-badge-warning',
                                'error'   => 't-badge-critical',
                                'success' => 't-badge-success',
                            ];
                            $typeLabels = [
                                'info'    => 'Informação',
                                'warning' => 'Aviso',
                                'error'   => 'Urgente',
                                'success' => 'Sucesso',
                            ];
                            $typeIcons = [
                                'info'    => 'fa-info-circle',
                                'warning' => 'fa-exclamation-triangle',
                                'error'   => 'fa-times-circle',
                                'success' => 'fa-check-circle',
                            ];
                            $badgeClass = $typeBadges[$notification->type] ?? 't-badge-info';
                            $typeLabel  = $typeLabels[$notification->type] ?? 'Info';
                            $typeIcon   = $typeIcons[$notification->type] ?? 'fa-info-circle';
                            ?>
                            <span class="<?= $badgeClass ?>" style="margin-left: 0;">
                                <i class="fa <?= $typeIcon ?>"></i>
                                <?= $typeLabel ?>
                            </span>
                        </div>
                        <div class="column no-grow text-align--right">
                            <span class="text-color--ink" style="font-size: 13px; white-space: nowrap; line-height: 1.4; display: inline-flex; align-items: center; gap: 4px;">
                                <i class="fa fa-user-o" style="font-size: 13px;"></i>
                                <?= $notification->source === 'admin' ? 'Admin' : 'Sistema' ?>
                                &nbsp;•&nbsp;
                                <i class="fa fa-clock-o" style="font-size: 13px;"></i>
                                <?= date('d/m/Y H:i', strtotime($notification->created_at)) ?>
                            </span>
                        </div>
                    </div>

                    <!-- Título -->
                    <h1 class="t-cards-title t-margin-none--top t-margin-small--bottom">
                        <?= CHtml::encode($notification->title) ?>
                    </h1>

                    <!-- Corpo -->
                    <div class="t-margin-small--bottom" style="font-size: 14px; line-height: 1.65; color: #333;">
                        <?= nl2br(CHtml::encode($notification->body)) ?>
                    </div>

                    <!-- Metadados extras -->
                    <?php if ($notification->source_url || $notification->source_event || $notification->expires_at): ?>
                        <div class="t-separator-primary t-margin-small--y"></div>
                        <div class="row wrap no-gap" style="gap: 16px;">
                            <?php if ($notification->source_url): ?>
                                <div class="column no-grow">
                                    <span class="text-color--ink" style="font-size: 12px; text-transform: uppercase; letter-spacing: .5px; font-weight: 600;">Link</span><br>
                                    <a href="<?= CHtml::encode($notification->source_url) ?>" target="_blank" class="text-bold" style="font-size: 13px;">
                                        <i class="fa fa-external-link"></i>
                                        <?= CHtml::encode($notification->source_url) ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            <?php if ($notification->source_event): ?>
                                <div class="column no-grow">
                                    <span class="text-color--ink" style="font-size: 12px; text-transform: uppercase; letter-spacing: .5px; font-weight: 600;">Evento</span><br>
                                    <code style="background: #f5f7f9; padding: 2px 8px; border-radius: 4px; font-size: 12px;">
                                        <?= CHtml::encode($notification->source_event) ?>
                                    </code>
                                </div>
                            <?php endif; ?>
                            <?php if ($notification->expires_at): ?>
                                <div class="column no-grow">
                                    <span class="text-color--ink" style="font-size: 12px; text-transform: uppercase; letter-spacing: .5px; font-weight: 600; display: block; margin-bottom: 4px;">Expira em</span>
                                    <span style="font-size: 13px; display: inline-flex; align-items: center; gap: 5px; white-space: nowrap;">
                                        <i class="fa fa-clock-o" style="font-size: 13px;"></i>
                                        <?= date('d/m/Y H:i', strtotime($notification->expires_at)) ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <div class="t-separator-primary t-margin-medium--y"></div>

                    <!-- Destinatários -->
                    <div class="row align-items--center no-gap t-margin-small--bottom">
                        <div class="column">
                            <h3 class="t-margin-none">
                                <i class="fa fa-users"></i>
                                Destinatários
                            </h3>
                        </div>
                        <div class="column no-grow">
                            <span class="t-badge-content">
                                <?= count($notification->recipients) ?>
                            </span>
                        </div>
                    </div>

                    <table class="tag-table-secondary">
                        <thead>
                            <tr>
                                <th>Usuário</th>
                                <th style="width: 120px;">Status</th>
                                <th style="width: 150px;">Lida em</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($notification->recipients as $r): ?>
                                <tr>
                                    <td><?= $r->user ? CHtml::encode($r->user->username) : "ID: {$r->user_fk}" ?></td>
                                    <td>
                                        <?php if ($r->is_read): ?>
                                            <span class="t-badge-success" style="margin: 0; font-size: 12px;">
                                                <i class="fa fa-check" style="font-size: 12px;"></i> Lida
                                            </span>
                                        <?php else: ?>
                                            <span class="t-badge" style="margin: 0; font-size: 12px;">
                                                <i class="fa fa-circle-o" style="font-size: 12px;"></i> Pendente
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-color--ink">
                                        <?= $r->read_at ? date('d/m/Y H:i', strtotime($r->read_at)) : '—' ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
