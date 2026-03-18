<?php

/**
 * Model ActiveRecord para a tabela `notification_recipient`.
 *
 * @property int    $id
 * @property int    $notification_fk
 * @property int    $user_fk
 * @property int    $is_read
 * @property string $read_at
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Notification $notification
 * @property Users        $user
 */
class NotificationRecipient extends TagModel
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'notification_recipient';
    }

    public function rules()
    {
        return [
            ['notification_fk, user_fk', 'required'],
            ['notification_fk, user_fk', 'numerical', 'integerOnly' => true],
            ['is_read', 'boolean'],
            ['read_at, created_at, updated_at', 'safe'],
        ];
    }

    public function relations()
    {
        return [
            'notification' => [self::BELONGS_TO, 'Notification', 'notification_fk'],
            'user' => [self::BELONGS_TO, 'Users', 'user_fk'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'notification_fk' => 'Notificação',
            'user_fk' => 'Usuário',
            'is_read' => 'Lida',
            'read_at' => 'Lida em',
            'created_at' => 'Criado em',
            'updated_at' => 'Atualizado em',
        ];
    }

    /**
     * Escopo: apenas não-lidas
     */
    public function unread()
    {
        $this->getDbCriteria()->mergeWith([
            'condition' => 'is_read = 0',
        ]);
        return $this;
    }

    /**
     * Escopo: do usuário
     */
    public function forUser($userId)
    {
        $this->getDbCriteria()->mergeWith([
            'condition' => 'user_fk = :uid',
            'params' => [':uid' => $userId],
        ]);
        return $this;
    }

    /**
     * Marca como lida
     */
    public function markRead()
    {
        $this->is_read = 1;
        $this->read_at = date('Y-m-d H:i:s');
        return $this->save(false, ['is_read', 'read_at']);
    }
}
