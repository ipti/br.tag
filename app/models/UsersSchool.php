<?php

/**
 * This is the model class for table "users_school".
 *
 * The followings are the available columns in table 'users_school':
 * @property integer $id
 * @property string $school_fk
 * @property integer $user_fk
 *
 * The followings are the available model relations:
 * @property SchoolIdentification $schoolFk
 * @property Users $userFk
 */
class UsersSchool extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return UsersSchool the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'users_school';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('school_fk, user_fk', 'required'),
            array('user_fk', 'numerical', 'integerOnly'=>true),
            array('school_fk', 'length', 'max'=>11),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, school_fk, user_fk', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'schoolFk' => array(self::BELONGS_TO, 'SchoolIdentification', 'school_fk'),
            'userFk' => array(self::BELONGS_TO, 'Users', 'user_fk'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => Yii::t('default', 'ID'),
            'school_fk' => Yii::t('default', 'School Fk'),
            'user_fk' => Yii::t('default', 'User Fk'),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria=new CDbCriteria();

        $criteria->compare('id', $this->id);
        $criteria->compare('school_fk', $this->school_fk, true);
        $criteria->compare('user_fk', $this->user_fk);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }
}
