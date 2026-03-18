<?php

/**
 * Model ActiveRecord para a tabela `notification`.
 *
 * @property int    $id
 * @property string $title
 * @property string $body
 * @property string $type         enum: info, warning, error, success
 * @property string $source       enum: admin, system
 * @property string $source_event ex: enrollment.created
 * @property string $source_url
 * @property int    $created_by
 * @property int    $school_fk
 * @property string $created_at
 * @property string $updated_at
 * @property string $expires_at
 *
 * @property NotificationRecipient[] $recipients
 * @property SchoolIdentification    $school
 */
class Notification extends TagModel
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'notification';
    }

    public function rules()
    {
        return [
            ['title, body', 'required'],
            ['title', 'length', 'max' => 255],
            ['type', 'in', 'range' => ['info', 'warning', 'error', 'success']],
            ['source', 'in', 'range' => ['admin', 'system']],
            ['source_event', 'length', 'max' => 100],
            ['source_url', 'length', 'max' => 500],
            ['created_by, school_fk', 'numerical', 'integerOnly' => true, 'allowEmpty' => true, 'min' => 0],
            ['expires_at, updated_at', 'safe'],
        ];
    }

    public function relations()
    {
        return [
            'recipients' => [self::HAS_MANY, 'NotificationRecipient', 'notification_fk'],
            'school' => [self::BELONGS_TO, 'SchoolIdentification', 'school_fk'],
            'creator' => [self::BELONGS_TO, 'Users', 'created_by'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Título',
            'body' => 'Mensagem',
            'type' => 'Tipo',
            'source' => 'Origem',
            'source_event' => 'Evento',
            'source_url' => 'URL',
            'created_by' => 'Criado por',
            'school_fk' => 'Escola',
            'created_at' => 'Criado em',
            'updated_at' => 'Atualizado em',
            'expires_at' => 'Expira em',
        ];
    }

    /**
     * Escopo: exclui notificações expiradas
     */
    public function active()
    {
        $this->getDbCriteria()->mergeWith([
            'condition' => 'expires_at IS NULL OR expires_at > NOW()',
        ]);
        return $this;
    }
}
