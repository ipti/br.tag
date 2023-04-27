<?php

/**
 * This is the model class for table "provision_accounts".
 *
 * The followings are the available columns in table 'provision_accounts':
 * @property integer $id
 * @property string $cod_unidade_gestora
 * @property string $name_unidade_gestora
 * @property string $cpf_responsavel
 * @property string $cpf_gestor
 * 
 */
class ProvisionAccounts extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'provision_accounts';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cod_unidade_gestora, name_unidade_gestora, cpf_responsavel, cpf_gestor', 'required'),
			array('cod_unidade_gestora', 'length', 'max'=>30),
			array('name_unidade_gestora', 'length', 'max'=>150),
			array('cpf_responsavel, cpf_gestor', 'length', 'max'=>11),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, cod_unidade_gestora, name_unidade_gestora, cpf_responsavel, cpf_gestor', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'cod_unidade_gestora' => 'Código Unidade Gestora',
			'name_unidade_gestora' => 'Nome Unidade Gestora',
			'cpf_responsavel' => 'CPF do Responsável',
			'cpf_gestor' => 'CPF do Gestor',
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
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('cod_unidade_gestora',$this->cod_unidade_gestora,true);
		$criteria->compare('name_unidade_gestora',$this->name_unidade_gestora,true);
		$criteria->compare('cpf_responsavel',$this->cpf_responsavel,true);
		$criteria->compare('cpf_gestor',$this->cpf_gestor,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ProvisionAccounts the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
