<?php

/*
 * ARQUIVO GERADO AUTOMATICAMENTE
 * ================================
 * Gerado por: scripts/generate-routes.php
 * Comando:    composer run routes:generate
 *
 * NÃO EDITE ESTE ARQUIVO MANUALMENTE.
 * Qualquer alteração será sobrescrita na próxima geração.
 *
 * Para adicionar ou renomear rotas, altere os controllers correspondentes
 * e re-execute: composer run routes:generate -- notifications
 */

class NotificationsRoutes
{
    // InboxController
    public const INBOX_INDEX = 'notifications/inbox/index';
    public const INBOX_MARKALLREAD = 'notifications/inbox/markAllRead';
    public const INBOX_MARKREAD = 'notifications/inbox/markRead';
    public const INBOX_RECENT = 'notifications/inbox/recent';
    public const INBOX_UNREADCOUNT = 'notifications/inbox/unreadCount';

    // NotificationsController
    public const NOTIFICATIONS_CREATE = 'notifications/notifications/create';
    public const NOTIFICATIONS_DELETE = 'notifications/notifications/delete';
    public const NOTIFICATIONS_INDEX = 'notifications/notifications/index';
    public const NOTIFICATIONS_VIEW = 'notifications/notifications/view';

    public static function url(string $route, array $params = []): string
    {
        return Yii::app()->createUrl($route, $params);
    }
}
