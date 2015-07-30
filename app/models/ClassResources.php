<?php

/**
 * This is the model class for table "class_objective".
 *
 * The followings are the available columns in table 'class_resource':
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $type
 * @property string $fkid
 *
 * The followings are the available model relations:
 * @property ClassHasContent[] $ClassHasContent
 */
class ClassResources extends CActiveRecord {

    const CONTENT = 1;
    const RESOURCE = 2;
    const TYPE = 3;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'class_resource';
    }
    
    public function behaviors() {
            return [
                'afterSave'=>[
                    'class'=>'application.behaviors.CAfterSaveBehavior',
                    'schoolInepId' => Yii::app()->user->school,
                ],
            ];
        }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, description, type', 'required'),
            array('name', 'length', 'max' => 45),
            array('description', 'length', 'max' => 200),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id,name, description,type', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'ClassHasContent' => array(self::HAS_MANY, 'ClassHasContent', 'content_fk'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => Yii::t('default', 'ID'),
            'name' => Yii::t('default', 'Name'),
            'description' => Yii::t('default', 'Description'),
            'type' => Yii::t('default', 'Type'),
        );
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
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('type', $this->type);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ClassResources the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
