<?php

/**
 * Console command para limpar notificações expiradas.
 *
 * Uso:
 *   php yiic purgenotifications
 *
 * Cron (rodar diariamente às 3h):
 *   0 3 * * * php /app/yiic purgenotifications
 */
class PurgeNotificationsCommand extends CConsoleCommand
{
    public function run($args)
    {
        echo "Purging expired notifications...\n";

        $deleted = Yii::app()->db->createCommand()
            ->delete('notification', 'expires_at IS NOT NULL AND expires_at < NOW()');

        echo "Done. Removed {$deleted} expired notification(s).\n";

        return 0;
    }

    public function getHelp()
    {
        return <<<EOD
USAGE
  yiic purgenotifications

DESCRIPTION
  Removes notifications that have passed their expiration date (expires_at < NOW()).
  Orphaned recipients are automatically deleted via CASCADE.

  Recommended: run daily via cron.
EOD;
    }
}
