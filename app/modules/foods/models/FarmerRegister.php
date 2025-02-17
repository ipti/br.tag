
<?php

/**
 * This is the model class for table "farmer_register".
 *
 * The followings are the available columns in table 'farmer_register':
 * @property integer $id
 * @property string $name
 * @property string $cpf
 * @property string $phone
 * @property string $group_type
 * @property string $reference_id
 * @property string $status
 *
 * The followings are the available model relations:
 * @property FarmerFoods[] $farmerFoods
 * @property FoodRequestVsFarmerRegister[] $foodRequestVsFarmerRegisters
 */
class FarmerRegister extends TagModel
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'farmer_register';
    }


    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, cpf', 'required'),
            array('name', 'length', 'max'=>100),
            array('cpf, phone', 'length', 'max'=>11),
            array('group_type', 'length', 'max'=>21),
            array('reference_id', 'length', 'max'=>36),
            array('status', 'length', 'max'=>7),
            // The following rule is used by search().
            array('id, name, cpf, phone, group_type, reference_id, status', 'safe', 'on'=>'search'),
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
            'farmerFoods' => array(self::HAS_MANY, 'FarmerFoods', 'farmer_fk'),
            'foodRequestVsFarmerRegisters' => array(self::HAS_MANY, 'FoodRequestVsFarmerRegister', 'farmer_fk'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Nome',
			'cpf' => 'CPF',
			'phone' => 'Telefone',
			'group_type' => 'Tipo do grupo',
			'reference_id' => 'Reference Id',
			'status' => 'Status',
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
    public function search($showAll)
    {

        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id);
        $criteria->compare('name',$this->name,true);
        $criteria->compare('cpf',$this->cpf,true);
        $criteria->compare('phone',$this->phone,true);
        $criteria->compare('group_type',$this->group_type,true);
        $criteria->compare('reference_id',$this->reference_id,true);
        $criteria->compare('status', $this->status, true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return FarmerRegister the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}
