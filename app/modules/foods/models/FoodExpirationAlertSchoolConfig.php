<?php

/**
 * This is the model class for table "food_expiration_alert_school_config".
 *
 * The followings are the available columns in table 'food_expiration_alert_school_config':
 * @property string $school_fk
 * @property integer $default_days
 * @property string $created_at
 * @property string $updated_at
 */
class FoodExpirationAlertSchoolConfig extends TagModel
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'food_expiration_alert_school_config';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return [
            ['school_fk', 'required'],
            ['school_fk', 'length', 'max' => 8],
            ['default_days', 'numerical', 'integerOnly' => true, 'min' => 1],
            ['created_at, updated_at', 'safe'],
        ];
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return [];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'school_fk' => 'Escola',
            'default_days' => 'Dias de antecedência (padrão)',
            'created_at' => 'Criado em',
            'updated_at' => 'Atualizado em',
        ];
    }

    /**
     * @return int|null prazo padrão (em dias) configurado para a escola,
     * usado por qualquer alimento que não pertença a nenhum grupo de alerta.
     * Retorna null quando a escola ainda não configurou nenhum prazo — nesse
     * caso, o alerta de vencimento fica desligado para esses alimentos até
     * que a própria escola o configure.
     */
    public static function getDefaultAlertDays(string $schoolFk): ?int
    {
        $config = self::model()->findByPk($schoolFk);

        return $config !== null ? (int) $config->default_days : null;
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return FoodExpirationAlertSchoolConfig the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
