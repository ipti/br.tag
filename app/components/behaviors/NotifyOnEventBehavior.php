<?php

/**
 * Behavior para disparar notificações automáticas em eventos de ActiveRecord.
 *
 * Uso no model:
 *   public function behaviors()
 *   {
 *       return [
 *           'notifyBehavior' => [
 *               'class' => 'application.components.behaviors.NotifyOnEventBehavior',
 *               'events' => [
 *                   'afterSave' => [
 *                       'title' => 'Matrícula atualizada',
 *                       'body' => function($model) { return "Aluno {$model->student->name}"; },
 *                       'targetUser' => function($model) { return $model->created_by; },
 *                       'sourceEvent' => TNotificationEvent::ENROLLMENT_CREATED,
 *                       'onlyNew' => true, // só dispara em INSERT, não UPDATE
 *                   ],
 *               ],
 *           ],
 *       ];
 *   }
 */
class NotifyOnEventBehavior extends CActiveRecordBehavior
{
    /**
     * @var array Configuração dos eventos e notificações.
     * Keys = nome do evento AR (afterSave, afterDelete, etc.)
     * Values = array com: title, body, targetUser, sourceEvent, type, onlyNew
     */
    public $events = [];

    public function afterSave($event)
    {
        $this->handleEvent('afterSave', $event);
    }

    public function afterDelete($event)
    {
        $this->handleEvent('afterDelete', $event);
    }

    /**
     * Processa o evento e dispara notificação se configurado.
     */
    protected function handleEvent($eventName, $event)
    {
        if (!isset($this->events[$eventName])) {
            return;
        }

        $config = $this->events[$eventName];
        $model = $this->owner;

        // Se onlyNew e não é novo registro, ignora
        if (!empty($config['onlyNew']) && $eventName === 'afterSave' && !$model->isNewRecord) {
            return;
        }

        // Resolve título (string ou callable)
        $title = is_callable($config['title']) ? call_user_func($config['title'], $model) : $config['title'];

        // Resolve corpo
        $body = is_callable($config['body']) ? call_user_func($config['body'], $model) : $config['body'];

        // Resolve destinatário
        $targetUserId = null;
        if (isset($config['targetUser'])) {
            $targetUserId = is_callable($config['targetUser'])
                ? call_user_func($config['targetUser'], $model)
                : $config['targetUser'];
        }

        $options = [
            'source' => 'system',
            'type' => $config['type'] ?? 'info',
        ];

        // Source event
        if (isset($config['sourceEvent'])) {
            $options['sourceEvent'] = $config['sourceEvent'];
        }

        // Source URL
        if (isset($config['sourceUrl'])) {
            $options['sourceUrl'] = is_callable($config['sourceUrl'])
                ? call_user_func($config['sourceUrl'], $model)
                : $config['sourceUrl'];
        }

        try {
            if ($targetUserId) {
                Yii::app()->notifier->notify($targetUserId, $title, $body, $options);
            } elseif (isset($config['targetRoles'])) {
                $options['targetRoles'] = $config['targetRoles'];
                if (isset($config['schoolId'])) {
                    $options['schoolId'] = is_callable($config['schoolId'])
                        ? call_user_func($config['schoolId'], $model)
                        : $config['schoolId'];
                }
                Yii::app()->notifier->broadcast($title, $body, $options);
            }
        } catch (Exception $e) {
            Yii::log(
                'NotifyOnEventBehavior error: ' . $e->getMessage(),
                CLogger::LEVEL_ERROR,
                'application.notifications'
            );
        }
    }
}
