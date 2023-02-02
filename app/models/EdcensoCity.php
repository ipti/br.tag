<?php

/**
 * This is the model class for table "edcenso_city".
 *
 * The followings are the available columns in table 'edcenso_city':
 * @property integer $id
 * @property integer $edcenso_uf_fk
 * @property string $name
 * @property string $cep_initial
 * @property string $cep_final
 * @property integer $ddd1
 * @property integer $ddd2
 *
 * The followings are the available model relations:
 * @property EdcensoUf $edcensoUfFk
 * @property EdcensoDistrict[] $edcensoDistricts
 * @property EdcensoRegionalEducationOrgan[] $edcensoRegionalEducationOrgans
 * @property InstructorDocumentsAndAddress[] $instructorDocumentsAndAddresses
 * @property InstructorIdentification[] $instructorIdentifications
 * @property SchoolIdentification[] $schoolIdentifications
 * @property StudentDocumentsAndAddress[] $studentDocumentsAndAddresses
 * @property StudentDocumentsAndAddress[] $studentDocumentsAndAddresses1
 * @property StudentIdentification[] $studentIdentifications
 */
class EdcensoCity extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return EdcensoCity the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'edcenso_city';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['id, edcenso_uf_fk, name', 'required'],
            ['id, edcenso_uf_fk, ddd1, ddd2', 'numerical', 'integerOnly' => true],
            ['name', 'length', 'max' => 50],
            ['cep_initial, cep_final', 'length', 'max' => 9],
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            ['id, edcenso_uf_fk, name, cep_initial, cep_final, ddd1, ddd2', 'safe', 'on' => 'search'],
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
            'edcensoUfFk' => [self::BELONGS_TO, 'EdcensoUf', 'edcenso_uf_fk'],
            'edcensoDistricts' => [self::HAS_MANY, 'EdcensoDistrict', 'edcenso_city_fk'],
            'edcensoRegionalEducationOrgans' => [self::HAS_MANY, 'EdcensoRegionalEducationOrgan', 'edcenso_city_fk'],
            'instructorDocumentsAndAddresses' => [self::HAS_MANY, 'InstructorDocumentsAndAddress', 'edcenso_city_fk'],
            'instructorIdentifications' => [self::HAS_MANY, 'InstructorIdentification', 'edcenso_city_fk'],
            'schoolIdentifications' => [self::HAS_MANY, 'SchoolIdentification', 'edcenso_city_fk'],
            'studentDocumentsAndAddresses' => [self::HAS_MANY, 'StudentDocumentsAndAddress', 'edcenso_city_fk'],
            'studentDocumentsAndAddresses1' => [self::HAS_MANY, 'StudentDocumentsAndAddress', 'notary_office_city_fk'],
            'studentIdentifications' => [self::HAS_MANY, 'StudentIdentification', 'edcenso_city_fk'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('default', 'ID'),
            'edcenso_uf_fk' => Yii::t('default', 'Edcenso Uf Fk'),
            'name' => Yii::t('default', 'Name'),
            'cep_initial' => Yii::t('default', 'Cep Initial'),
            'cep_final' => Yii::t('default', 'Cep Final'),
            'ddd1' => Yii::t('default', 'Ddd1'),
            'ddd2' => Yii::t('default', 'Ddd2'),
        ];
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria();

        $criteria->compare('id', $this->id);
        $criteria->compare('edcenso_uf_fk', $this->edcenso_uf_fk);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('cep_initial', $this->cep_initial, true);
        $criteria->compare('cep_final', $this->cep_final, true);
        $criteria->compare('ddd1', $this->ddd1);
        $criteria->compare('ddd2', $this->ddd2);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
        ]);
    }
}
