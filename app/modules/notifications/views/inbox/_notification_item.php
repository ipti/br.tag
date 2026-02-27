<?php
/**
 * @var NotificationRecipient $nr
 * @var Notification $n
 */
$typeColors = [
    'info'    => '#3498db',
    'warning' => '#f39c12',
    'error'   => '#e74c3c',
    'success' => '#2ecc71',
];
$typeIcons = [
    'info'    => 'fa-info-circle',
    'warning' => 'fa-exclamation-triangle',
    'error'   => 'fa-times-circle',
    'success' => 'fa-check-circle',
];
$typeBadges = [
    'info'    => 't-badge-info',
    'warning' => 't-badge-warning',
    'error'   => 't-badge-critical',
    'success' => 't-badge-success',
];
$color      = $typeColors[$n->type] ?? $typeColors['info'];
$icon       = $typeIcons[$n->type] ?? $typeIcons['info'];
$badgeClass = $typeBadges[$n->type] ?? 't-badge-info';
$isUnread   = !$nr->is_read;
?>

<div class="notification-item <?= $isUnread ? 'notification-item--unread' : '' ?>"
     style="display: flex; align-items: flex-start; gap: 16px; padding: 16px 18px;
            margin-bottom: 6px;
            background: <?= $isUnread ? '#f0f6ff' : '#fff' ?>;
            border-left: 4px solid <?= $color ?>;
            border-radius: 6px;
            box-shadow: 0 1px 3px rgba(0,0,0,.07);
            transition: background .2s, box-shadow .2s;">

    <!-- Ícone -->
    <div style="flex-shrink: 0; padding-top: 2px;">
        <i class="fa <?= $icon ?>" style="color: <?= $color ?>; font-size: 20px;"></i>
    </div>

    <!-- Conteúdo -->
    <div style="flex: 1; min-width: 0;">
        <div class="row justify-content--space-between align-items--center no-gap t-margin-none--y">
            <div class="column">
                <strong style="font-size: 14px; color: #1a2b3c;">
                    <?= CHtml::encode($n->title) ?>
                    <?php if ($isUnread): ?>
                        <span class="t-badge-critical" style="margin-left: 8px; font-size: 10px; vertical-align: middle;">Nova</span>
                    <?php endif; ?>
                </strong>
            </div>
            <div class="column no-grow" style="white-space: nowrap; padding-left: 12px;">
                <span class="text-color--ink" style="font-size: 12px;">
                    <?php
                    $diff = time() - strtotime($n->created_at);
                    if ($diff < 60) echo '<i class="fa fa-clock-o"></i> agora';
                    elseif ($diff < 3600) echo '<i class="fa fa-clock-o"></i> ' . floor($diff / 60) . ' min';
                    elseif ($diff < 86400) echo '<i class="fa fa-clock-o"></i> ' . floor($diff / 3600) . 'h';
                    else echo '<i class="fa fa-calendar"></i> ' . date('d/m/Y', strtotime($n->created_at));
                    ?>
                </span>
            </div>
        </div>

        <p class="text-color--ink t-margin-none--y" style="font-size: 13px; line-height: 1.5; margin-top: 4px;">
            <?= CHtml::encode(mb_substr($n->body, 0, 200)) ?>
            <?= mb_strlen($n->body) > 200 ? '...' : '' ?>
        </p>

        <div style="margin-top: 10px; display: flex; gap: 8px; flex-wrap: wrap; align-items: center;">
            <?php if ($n->source_url): ?>
                <a href="<?= CHtml::encode($n->source_url) ?>"
                   class="t-button-icon secondary nofloat"
                   style="padding: 4px 12px; font-size: 12px;" target="_blank">
                    <i class="fa fa-external-link"></i> Ver
                </a>
            <?php endif; ?>
            <?php if ($isUnread): ?>
                <a href="<?= Yii::app()->createUrl('notifications/inbox/markRead', ['id' => $nr->id]) ?>"
                   class="t-button-icon secondary nofloat js-mark-read"
                   data-id="<?= $nr->id ?>"
                   style="padding: 4px 12px; font-size: 12px;">
                    <i class="fa fa-check"></i> Marcar como lida
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>
