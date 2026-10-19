<?php

/**
 * This is the model class for table "student_food_allergy".
 *
 * The followings are the available columns in table 'student_food_allergy':
 * @property integer $id
 * @property integer $student_fk
 * @property string $allergy_type
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 *
 * The followings are the available model relations:
 * @property StudentIdentification $studentFk
 */
class StudentFoodAllergy extends TagModel
{
    public const TYPE_MILK = 'LEITE';
    public const TYPE_EGG = 'OVO';
    public const TYPE_FISH = 'PEIXE';
    public const TYPE_SHELLFISH = 'CRUSTACEOS_MOLUSCOS';
    public const TYPE_PEANUT = 'AMENDOIM';
    public const TYPE_PINEAPPLE = 'ABACAXI';
    public const TYPE_TREE_NUTS = 'CASTANHAS_OLEAGINOSAS';
    public const TYPE_SOY = 'SOJA';
    public const TYPE_WHEAT = 'TRIGO';
    public const TYPE_SESAME = 'GERGELIM';
    public const TYPE_OTHER = 'OUTRAS';

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'student_food_allergy';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return [
            ['student_fk, allergy_type', 'required'],
            ['student_fk', 'numerical', 'integerOnly' => true],
            ['allergy_type', 'length', 'max' => 30],
            ['description', 'length', 'max' => 255],
            ['created_at, updated_at', 'safe'],
            ['id, student_fk, allergy_type, description, created_at, updated_at', 'safe', 'on' => 'search'],
        ];
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return [
            'studentFk' => [self::BELONGS_TO, 'StudentIdentification', 'student_fk'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'student_fk' => 'Aluno',
            'allergy_type' => 'Alimento',
            'description' => 'Descrição',
            'created_at' => 'Criado em',
            'updated_at' => 'Atualizado em',
        ];
    }

    /**
     * @return array map of allergy_type => display label, in the order they should be presented.
     */
    public static function typeLabels(): array
    {
        return [
            self::TYPE_MILK => 'Leite',
            self::TYPE_EGG => 'Ovo',
            self::TYPE_FISH => 'Peixe',
            self::TYPE_SHELLFISH => 'Crustáceos e molusco',
            self::TYPE_PEANUT => 'Amendoim',
            self::TYPE_PINEAPPLE => 'Abacaxi',
            self::TYPE_TREE_NUTS => 'Castanhas e outras oleaginosas',
            self::TYPE_SOY => 'Soja',
            self::TYPE_WHEAT => 'Trigo',
            self::TYPE_SESAME => 'Gergelim',
            self::TYPE_OTHER => 'Outras',
        ];
    }

    /**
     * @return string the display label for this row (uses the free-text description for TYPE_OTHER).
     */
    public function getTypeLabel(): string
    {
        if ($this->allergy_type === self::TYPE_OTHER && !empty($this->description)) {
            return $this->description;
        }

        $labels = self::typeLabels();

        return $labels[$this->allergy_type] ?? $this->allergy_type;
    }

    /**
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search()
    {
        $criteria = new CDbCriteria();

        $criteria->compare('id', $this->id);
        $criteria->compare('student_fk', $this->student_fk);
        $criteria->compare('allergy_type', $this->allergy_type, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('created_at', $this->created_at, true);
        $criteria->compare('updated_at', $this->updated_at, true);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
        ]);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return StudentFoodAllergy the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
