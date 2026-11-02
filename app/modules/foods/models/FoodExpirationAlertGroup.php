<?php

/**
 * This is the model class for table "food_expiration_alert_group".
 *
 * The followings are the available columns in table 'food_expiration_alert_group':
 * @property integer $id
 * @property string $school_fk
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
            ['school_fk, name, alert_days', 'required'],
            ['school_fk', 'length', 'max' => 8],
            ['alert_days', 'numerical', 'integerOnly' => true, 'min' => 1],
            ['name', 'length', 'max' => 100],
            ['created_at, updated_at', 'safe'],
            ['id, school_fk, name, alert_days, created_at, updated_at', 'safe', 'on' => 'search'],
        ];
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return [
            'foods' => [self::MANY_MANY, 'Food', 'food_expiration_alert_group_food(group_fk, food_fk)'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'school_fk' => 'Escola',
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
        $criteria->compare('school_fk', $this->school_fk);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('alert_days', $this->alert_days);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
            'pagination' => false,
        ]);
    }

    /**
     * Mapa food_fk => alert_days considerando apenas os grupos da escola
     * informada. Usado para descobrir o prazo de um alimento sem precisar de
     * uma relação direta em `food` (o mesmo alimento pode estar em grupos
     * diferentes em escolas diferentes).
     *
     * @return array<int, int>
     */
    public static function getFoodAlertDaysMap(string $schoolFk): array
    {
        $rows = Yii::app()->db->createCommand()
            ->select('gf.food_fk AS food_fk, g.alert_days AS alert_days')
            ->from('food_expiration_alert_group g')
            ->join('food_expiration_alert_group_food gf', 'gf.group_fk = g.id')
            ->where('g.school_fk = :schoolFk', [':schoolFk' => $schoolFk])
            ->queryAll();

        $map = [];
        foreach ($rows as $row) {
            $map[(int) $row['food_fk']] = (int) $row['alert_days'];
        }

        return $map;
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
