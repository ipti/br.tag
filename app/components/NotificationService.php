<?php

/**
 * Serviço central de notificações do TAG.
 * Registrado em main.php como Yii::app()->notifier.
 *
 * Uso:
 *   // Notificação individual
 *   Yii::app()->notifier->notify($userId, 'Título', 'Corpo', [...]);
 *
 *   // Broadcast por role/escola
 *   Yii::app()->notifier->broadcast('Título', 'Corpo', [
 *       'targetRoles' => ['manager', 'coordinator'],
 *       'schoolId' => 123,
 *   ]);
 */
class NotificationService extends CApplicationComponent
{
    /**
     * @var int Dias padrão para expiração (0 = nunca expira)
     */
    public $defaultExpirationDays = 30;

    /**
     * Cria notificação para um usuário específico.
     *
     * @param int    $userId
     * @param string $title
     * @param string $body
     * @param array  $options  Keys: type, source, sourceEvent, sourceUrl, schoolId, expirationDays, createdBy
     * @return Notification
     */
    public function notify($userId, $title, $body, array $options = [])
    {
        $notification = $this->createNotification($title, $body, $options);

        $recipient = new NotificationRecipient();
        $recipient->notification_fk = $notification->id;
        $recipient->user_fk = $userId;
        $recipient->save();

        return $notification;
    }

    /**
     * Broadcast para múltiplos usuários, filtrado por role e/ou escola.
     *
     * @param string $title
     * @param string $body
     * @param array  $filters  Keys: targetRoles (array), schoolId (int), type, source, sourceEvent, sourceUrl, expirationDays, createdBy
     * @return Notification
     */
    public function broadcast($title, $body, array $filters = [])
    {
        $notification = $this->createNotification($title, $body, $filters);

        $userIds = $this->resolveRecipients($filters);

        foreach ($userIds as $uid) {
            $recipient = new NotificationRecipient();
            $recipient->notification_fk = $notification->id;
            $recipient->user_fk = $uid;
            $recipient->save();
        }

        return $notification;
    }

    /**
     * Conta notificações não-lidas de um usuário.
     *
     * @param int $userId
     * @return int
     */
    public function getUnreadCount($userId)
    {
        return (int) NotificationRecipient::model()
            ->forUser($userId)
            ->unread()
            ->with(['notification' => ['scopes' => 'active']])
            ->count();
    }

    /**
     * Busca notificações do usuário (paginadas).
     *
     * @param int $userId
     * @param int $limit
     * @param int $offset
     * @return NotificationRecipient[]
     */
    public function getUserNotifications($userId, $limit = 20, $offset = 0)
    {
        return NotificationRecipient::model()
            ->forUser($userId)
            ->with(['notification' => ['scopes' => 'active']])
            ->findAll([
                'order' => 'notification.created_at DESC',
                'limit' => $limit,
                'offset' => $offset,
            ]);
    }

    /**
     * Marca uma notificação como lida para o usuário.
     *
     * @param int $recipientId
     * @param int $userId
     * @return bool
     */
    public function markAsRead($recipientId, $userId)
    {
        $recipient = NotificationRecipient::model()->findByAttributes([
            'id' => $recipientId,
            'user_fk' => $userId,
        ]);

        if ($recipient) {
            return $recipient->markRead();
        }

        return false;
    }

    /**
     * Marca todas as notificações do usuário como lidas.
     *
     * @param int $userId
     * @return int linhas afetadas
     */
    public function markAllAsRead($userId)
    {
        return Yii::app()->db->createCommand()
            ->update('notification_recipient', [
                'is_read' => 1,
                'read_at' => date('Y-m-d H:i:s'),
            ], 'user_fk = :uid AND is_read = 0', [
                ':uid' => $userId,
            ]);
    }

    /**
     * Remove notificações expiradas.
     *
     * @return int linhas removidas
     */
    public function purgeExpired()
    {
        return Yii::app()->db->createCommand()
            ->delete('notification', 'expires_at IS NOT NULL AND expires_at < NOW()');
    }

    // ===================== Métodos privados =====================

    /**
     * Cria o registro de notificação no banco.
     */
    private function createNotification($title, $body, array $options = [])
    {
        $notification = new Notification();
        $notification->title = $title;
        $notification->body = $body;
        $notification->type = $options['type'] ?? 'info';
        $notification->source = $options['source'] ?? 'system';
        $notification->created_by = (isset($options['createdBy']) && $options['createdBy'] !== null) ? (int)$options['createdBy'] : null;
        $notification->school_fk = $options['schoolId'] ?? null;
        $notification->source_url = $options['sourceUrl'] ?? null;

        // source_event: aceita enum ou string
        $event = $options['sourceEvent'] ?? null;
        if ($event instanceof TNotificationEvent) {
            $notification->source_event = $event->value;
        } else {
            $notification->source_event = $event;
        }

        // Expiração
        $days = $options['expirationDays'] ?? $this->defaultExpirationDays;
        if ($days > 0) {
            $notification->expires_at = date('Y-m-d H:i:s', strtotime("+{$days} days"));
        }

        if (!$notification->save()) {
            Yii::log(
                'Falha ao salvar notificação: ' . print_r($notification->getErrors(), true),
                CLogger::LEVEL_ERROR,
                'application.notifications'
            );
        }

        return $notification;
    }

    /**
     * Resolve IDs de usuários baseado nos filtros (roles e escola).
     *
     * @param array $filters
     * @return array IDs de usuários
     */
    private function resolveRecipients(array $filters)
    {
        $criteria = new CDbCriteria();
        $criteria->select = 'DISTINCT t.id';
        $criteria->join = 'INNER JOIN auth_assignment aa ON aa.userid = t.id';

        // Filtro por role
        if (!empty($filters['targetRoles'])) {
            $roles = $filters['targetRoles'];
            // Aceita enums TRole ou strings
            $roleValues = array_map(function ($r) {
                return ($r instanceof TRole) ? $r->value : $r;
            }, $roles);
            $criteria->addInCondition('aa.itemname', $roleValues);
        }

        // Filtro por escola
        if (!empty($filters['schoolId'])) {
            $criteria->join .= ' INNER JOIN users_school us ON us.user_fk = t.id';
            $criteria->addCondition('us.school_fk = :schoolId');
            $criteria->params[':schoolId'] = $filters['schoolId'];
        }

        $users = Users::model()->findAll($criteria);

        return array_map(function ($u) {
            return $u->id;
        }, $users);
    }
}
