<?php
/**
 * @var NotificationRecipient[] $notifications
 * @var int $unreadCount
 * @var int $page
 * @var int $totalPages
 */
$this->pageTitle = 'Minhas Notificações';
?>

<div class="main">
    <div class="row">
        <div class="column is-two-thirds">
            <div class="row align-items--center justify-content--space-between t-margin-small--bottom no-gap">
                <div class="column no-grow align-items--center">
                    <h1 class="t-margin-none--y">
                        <i class="fa fa-inbox"></i> Minhas Notificações
                    </h1>
                    <?php if ($unreadCount > 0): ?>
                        <span class="t-badge-critical t-margin-small--left" style="font-size: 14px;">
                            <?= $unreadCount ?> não lida<?= $unreadCount > 1 ? 's' : '' ?>
                        </span>
                    <?php endif; ?>
                </div>
                <?php if ($unreadCount > 0): ?>
                    <div class="column no-grow">
                        <a href="<?= Yii::app()->createUrl('notifications/inbox/markAllRead') ?>"
                           class="t-button-secondary nofloat" style="padding: 6px 12px; margin: 0;">
                            <i class="fa fa-check-double"></i> Marcar todas
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <div class="notification-inbox-list">
                <?php if (empty($notifications)): ?>
                    <div class="text-align--center t-padding-large--y text-color--ink" style="padding-top: 60px;">
                        <i class="fa fa-bell-slash" style="font-size: 48px; display: block; margin-bottom: 16px;"></i>
                        Nenhuma notificação por enquanto.
                    </div>
                <?php endif; ?>

                <?php foreach ($notifications as $nr): ?>
                    <?php $n = $nr->notification; ?>
                    <?php if (!$n) continue; ?>
                    <?php $this->renderPartial('_notification_item', ['nr' => $nr, 'n' => $n]); ?>
                <?php endforeach; ?>
            </div>

            <!-- Paginação -->
            <?php if ($totalPages > 1): ?>
                <div class="row justify-content--center t-margin-large--top">
                    <div class="t-buttons-container auto-width no-gap">
                        <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                            <a href="<?= Yii::app()->createUrl('notifications/inbox/index', ['page' => $p]) ?>"
                               class="<?= $p === $page ? 't-button-primary' : 't-button-secondary' ?> nofloat"
                               style="padding: 4px 12px; margin: 0 4px; min-width: 32px; text-align: center;">
                                <?= $p ?>
                            </a>
                        <?php endfor; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
