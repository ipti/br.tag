<?php

/**
 * This is the model class for table "food_expiration_alert_group".
 *
 * The followings are the available columns in table 'food_expiration_alert_group':
 * @property integer $id
 * @property string $name
 * @property integer $alert_days
 * @property string $created_at
 * @property string $updated_at
 *
 * The followings are the available model relations:
 * @property Food[] $foods
 */
class FoodExpirationAlertGroup extends TagModel
{
    public const DEFAULT_DAYS_PARAMETER_KEY = 'FOODS_EXPIRATION_ALERT_DAYS_DEFAULT';

    /**
     * @return int prazo padrão (em dias) configurado para o município, usado
     * por qualquer alimento que não pertença a nenhum grupo de alerta.
     */
    public static function getDefaultAlertDays(): int
    {
        $config = InstanceConfig::model()->findByAttributes(['parameter_key' => self::DEFAULT_DAYS_PARAMETER_KEY]);

        return $config !== null ? (int) $config->value : 30;
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'food_expiration_alert_group';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return [
            ['name, alert_days', 'required'],
            ['alert_days', 'numerical', 'integerOnly' => true, 'min' => 1],
            ['name', 'length', 'max' => 100],
            ['created_at, updated_at', 'safe'],
            ['id, name, alert_days, created_at, updated_at', 'safe', 'on' => 'search'],
        ];
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return [
            'foods' => [self::HAS_MANY, 'Food', 'expiration_alert_group_fk'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Nome do grupo',
            'alert_days' => 'Dias de antecedência',
            'created_at' => 'Criado em',
            'updated_at' => 'Atualizado em',
        ];
    }

    /**
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search()
    {
        $criteria = new CDbCriteria();

        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('alert_days', $this->alert_days);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
            'pagination' => false,
        ]);
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return FoodExpirationAlertGroup the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
