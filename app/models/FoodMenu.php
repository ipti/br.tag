<?php

/**
 * This is the model class for table "food_menu".
 *
 * The followings are the available columns in table 'food_menu':
 * @property integer $id
 * @property string $description
 * @property string $observation
 * @property string $start_date
 * @property string $final_date
 * @property string $week
 * @property integer $include_saturday
 *
 * The followings are the available model relations:
 * @property FoodMenuMeal[] $foodMenuMeals
 * @property FoodMenuVsFoodPublicTarget[] $foodMenuVsFoodPublicTargets
 */
class FoodMenu extends TagModel
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'food_menu';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['description, start_date, final_date', 'required'],
            ['include_saturday', 'numerical', 'integerOnly' => true],
            ['description, observation', 'length', 'max' => 100],
            ['week', 'length', 'max' => 1],
            ['id, description, observation, start_date, final_date, week, include_saturday', 'safe', 'on' => 'search'],
        ];
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return [
            'foodMenuMeals' => [self::HAS_MANY, 'FoodMenuMeal', 'food_menuId'],
            'foodMenuVsFoodPublicTargets' => [self::HAS_MANY, 'FoodMenuVsFoodPublicTarget', 'food_menu_fk'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'description' => 'Description',
            'observation' => 'Observation',
            'start_date' => 'Start Date',
            'final_date' => 'Final Date',
            'week' => 'Week',
            'include_saturday' => 'Include Saturday',
        ];
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search()
    {
        $criteria = new CDbCriteria();

        $criteria->compare('id', $this->id);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('observation', $this->observation, true);
        $criteria->compare('start_date', $this->start_date, true);
        $criteria->compare('final_date', $this->final_date, true);
        $criteria->compare('week', $this->week, true);
        $criteria->compare('include_saturday', $this->include_saturday);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
        ]);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return FoodMenu the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
