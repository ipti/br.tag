<?php

/**
 * This is the model class for table "food".
 *
 * The followings are the available columns in table 'food':
 * @property integer $tacoId
 * @property string $description
 * @property string $scientificName
 * @property integer $protein
 * @property integer $lipids
 * @property integer $carbohydrate
 * @property integer $ashes
 * @property integer $energyKcal
 * @property integer $energyKj
 * @property integer $moisture
 * @property integer $fiber
 * @property integer $mineralsCalcium
 * @property integer $mineralsIron
 * @property integer $mineralsMagnesium
 * @property integer $mineralsPhosphorus
 * @property integer $mineralsPotassium
 * @property integer $mineralsSodium
 * @property integer $mineralsZinc
 * @property integer $mineralsCopper
 * @property integer $mineralsManganese
 * @property integer $vitaminsRetinol
 * @property integer $vitaminsRE
 * @property integer $vitaminsRAE
 * @property integer $vitaminsC
 * @property integer $vitaminsThiamine
 * @property integer $vitaminsRiboflavin
 * @property integer $vitaminsNiacin
 * @property integer $vitaminsB6
 * @property integer $aminoacidsAlanine
 * @property integer $aminoacidsArginine
 * @property integer $aminoacidsAspartic
 * @property integer $aminoacidsCystine
 * @property integer $aminoacidsGlutamic
 * @property integer $aminoacidsGlycine
 * @property integer $aminoacidsHistidine
 * @property integer $aminoacidsIsoleucine
 * @property integer $aminoacidsLeucine
 * @property integer $aminoacidsLysine
 * @property integer $aminoacidsMethionine
 * @property integer $aminoacidsPhenylalanine
 * @property integer $aminoacidsProline
 * @property integer $aminoacidsSerine
 * @property integer $aminoacidsThreonine
 * @property integer $aminoacidsTryptophan
 * @property integer $aminoacidsTyrosine
 * @property integer $aminoacidsValine
 * @property integer $butanoic
 * @property integer $hexanoic
 * @property integer $octanoic
 * @property integer $decanoic
 * @property integer $dodecanoic
 * @property integer $tetracanoic
 * @property integer $hexadecanoic
 * @property integer $octadecanoic
 * @property integer $eicosanoic
 * @property integer $docosanoic
 * @property integer $tetracosanoic
 * @property integer $tetracenoic
 * @property integer $hexadecenoic
 * @property integer $octadecenoic
 * @property integer $eicosenoic
 * @property integer $docosenoic
 * @property integer $tetracosenoic
 * @property integer $octadecadienoic
 * @property integer $octadecatrienoic
 * @property integer $octadecatetraenoic
 * @property integer $eicosadienoic
 * @property integer $eicosatrienoic
 * @property integer $eicosatetraenoic
 * @property integer $eicosapentaenoic
 * @property integer $docosapentaenoic
 * @property integer $docosahexaenoic
 * @property integer $transOctadecenoic
 * @property integer $transOctadecadienoic
 *
 * The followings are the available model relations:
 * @property FoodIngredient[] $foodIngredients
 * @property FoodIngredientAlternatives[] $foodIngredientAlternatives
 * @property FoodInventory[] $foodInventories
 */
class Food extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'food';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('tacoId, description, protein, lipids, carbohydrate, ashes, energyKcal, energyKj, moisture, fiber, mineralsCalcium, mineralsIron, mineralsMagnesium, mineralsPhosphorus, mineralsPotassium, mineralsSodium, mineralsZinc, mineralsCopper, mineralsManganese, vitaminsRetinol, vitaminsRE, vitaminsRAE, vitaminsC, vitaminsThiamine, vitaminsRiboflavin, vitaminsNiacin, vitaminsB6, aminoacidsAlanine, aminoacidsArginine, aminoacidsAspartic, aminoacidsCystine, aminoacidsGlutamic, aminoacidsGlycine, aminoacidsHistidine, aminoacidsIsoleucine, aminoacidsLeucine, aminoacidsLysine, aminoacidsMethionine, aminoacidsPhenylalanine, aminoacidsProline, aminoacidsSerine, aminoacidsThreonine, aminoacidsTryptophan, aminoacidsTyrosine, aminoacidsValine, butanoic, hexanoic, octanoic, decanoic, dodecanoic, tetracanoic, hexadecanoic, octadecanoic, eicosanoic, docosanoic, tetracosanoic, tetracenoic, hexadecenoic, octadecenoic, eicosenoic, docosenoic, tetracosenoic, octadecadienoic, octadecatrienoic, octadecatetraenoic, eicosadienoic, eicosatrienoic, eicosatetraenoic, eicosapentaenoic, docosapentaenoic, docosahexaenoic', 'required'),
			array('tacoId, protein, lipids, carbohydrate, ashes, energyKcal, energyKj, moisture, fiber, mineralsCalcium, mineralsIron, mineralsMagnesium, mineralsPhosphorus, mineralsPotassium, mineralsSodium, mineralsZinc, mineralsCopper, mineralsManganese, vitaminsRetinol, vitaminsRE, vitaminsRAE, vitaminsC, vitaminsThiamine, vitaminsRiboflavin, vitaminsNiacin, vitaminsB6, aminoacidsAlanine, aminoacidsArginine, aminoacidsAspartic, aminoacidsCystine, aminoacidsGlutamic, aminoacidsGlycine, aminoacidsHistidine, aminoacidsIsoleucine, aminoacidsLeucine, aminoacidsLysine, aminoacidsMethionine, aminoacidsPhenylalanine, aminoacidsProline, aminoacidsSerine, aminoacidsThreonine, aminoacidsTryptophan, aminoacidsTyrosine, aminoacidsValine, butanoic, hexanoic, octanoic, decanoic, dodecanoic, tetracanoic, hexadecanoic, octadecanoic, eicosanoic, docosanoic, tetracosanoic, tetracenoic, hexadecenoic, octadecenoic, eicosenoic, docosenoic, tetracosenoic, octadecadienoic, octadecatrienoic, octadecatetraenoic, eicosadienoic, eicosatrienoic, eicosatetraenoic, eicosapentaenoic, docosapentaenoic, docosahexaenoic, transOctadecenoic, transOctadecadienoic', 'numerical', 'integerOnly'=>true),
			array('description, scientificName', 'length', 'max'=>191),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('tacoId, description, scientificName, protein, lipids, carbohydrate, ashes, energyKcal, energyKj, moisture, fiber, mineralsCalcium, mineralsIron, mineralsMagnesium, mineralsPhosphorus, mineralsPotassium, mineralsSodium, mineralsZinc, mineralsCopper, mineralsManganese, vitaminsRetinol, vitaminsRE, vitaminsRAE, vitaminsC, vitaminsThiamine, vitaminsRiboflavin, vitaminsNiacin, vitaminsB6, aminoacidsAlanine, aminoacidsArginine, aminoacidsAspartic, aminoacidsCystine, aminoacidsGlutamic, aminoacidsGlycine, aminoacidsHistidine, aminoacidsIsoleucine, aminoacidsLeucine, aminoacidsLysine, aminoacidsMethionine, aminoacidsPhenylalanine, aminoacidsProline, aminoacidsSerine, aminoacidsThreonine, aminoacidsTryptophan, aminoacidsTyrosine, aminoacidsValine, butanoic, hexanoic, octanoic, decanoic, dodecanoic, tetracanoic, hexadecanoic, octadecanoic, eicosanoic, docosanoic, tetracosanoic, tetracenoic, hexadecenoic, octadecenoic, eicosenoic, docosenoic, tetracosenoic, octadecadienoic, octadecatrienoic, octadecatetraenoic, eicosadienoic, eicosatrienoic, eicosatetraenoic, eicosapentaenoic, docosapentaenoic, docosahexaenoic, transOctadecenoic, transOctadecadienoic', 'safe', 'on'=>'search'),
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
			'foodIngredients' => array(self::HAS_MANY, 'FoodIngredient', 'food_id_fk'),
			'foodIngredientAlternatives' => array(self::HAS_MANY, 'FoodIngredientAlternatives', 'food_fk'),
			'foodInventories' => array(self::HAS_MANY, 'FoodInventory', 'food_fk'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'tacoId' => 'Taco',
			'description' => 'Description',
			'scientificName' => 'Scientific Name',
			'protein' => 'Protein',
			'lipids' => 'Lipids',
			'carbohydrate' => 'Carbohydrate',
			'ashes' => 'Ashes',
			'energyKcal' => 'Energy Kcal',
			'energyKj' => 'Energy Kj',
			'moisture' => 'Moisture',
			'fiber' => 'Fiber',
			'mineralsCalcium' => 'Minerals Calcium',
			'mineralsIron' => 'Minerals Iron',
			'mineralsMagnesium' => 'Minerals Magnesium',
			'mineralsPhosphorus' => 'Minerals Phosphorus',
			'mineralsPotassium' => 'Minerals Potassium',
			'mineralsSodium' => 'Minerals Sodium',
			'mineralsZinc' => 'Minerals Zinc',
			'mineralsCopper' => 'Minerals Copper',
			'mineralsManganese' => 'Minerals Manganese',
			'vitaminsRetinol' => 'Vitamins Retinol',
			'vitaminsRE' => 'Vitamins Re',
			'vitaminsRAE' => 'Vitamins Rae',
			'vitaminsC' => 'Vitamins C',
			'vitaminsThiamine' => 'Vitamins Thiamine',
			'vitaminsRiboflavin' => 'Vitamins Riboflavin',
			'vitaminsNiacin' => 'Vitamins Niacin',
			'vitaminsB6' => 'Vitamins B6',
			'aminoacidsAlanine' => 'Aminoacids Alanine',
			'aminoacidsArginine' => 'Aminoacids Arginine',
			'aminoacidsAspartic' => 'Aminoacids Aspartic',
			'aminoacidsCystine' => 'Aminoacids Cystine',
			'aminoacidsGlutamic' => 'Aminoacids Glutamic',
			'aminoacidsGlycine' => 'Aminoacids Glycine',
			'aminoacidsHistidine' => 'Aminoacids Histidine',
			'aminoacidsIsoleucine' => 'Aminoacids Isoleucine',
			'aminoacidsLeucine' => 'Aminoacids Leucine',
			'aminoacidsLysine' => 'Aminoacids Lysine',
			'aminoacidsMethionine' => 'Aminoacids Methionine',
			'aminoacidsPhenylalanine' => 'Aminoacids Phenylalanine',
			'aminoacidsProline' => 'Aminoacids Proline',
			'aminoacidsSerine' => 'Aminoacids Serine',
			'aminoacidsThreonine' => 'Aminoacids Threonine',
			'aminoacidsTryptophan' => 'Aminoacids Tryptophan',
			'aminoacidsTyrosine' => 'Aminoacids Tyrosine',
			'aminoacidsValine' => 'Aminoacids Valine',
			'butanoic' => 'Butanoic',
			'hexanoic' => 'Hexanoic',
			'octanoic' => 'Octanoic',
			'decanoic' => 'Decanoic',
			'dodecanoic' => 'Dodecanoic',
			'tetracanoic' => 'Tetracanoic',
			'hexadecanoic' => 'Hexadecanoic',
			'octadecanoic' => 'Octadecanoic',
			'eicosanoic' => 'Eicosanoic',
			'docosanoic' => 'Docosanoic',
			'tetracosanoic' => 'Tetracosanoic',
			'tetracenoic' => 'Tetracenoic',
			'hexadecenoic' => 'Hexadecenoic',
			'octadecenoic' => 'Octadecenoic',
			'eicosenoic' => 'Eicosenoic',
			'docosenoic' => 'Docosenoic',
			'tetracosenoic' => 'Tetracosenoic',
			'octadecadienoic' => 'Octadecadienoic',
			'octadecatrienoic' => 'Octadecatrienoic',
			'octadecatetraenoic' => 'Octadecatetraenoic',
			'eicosadienoic' => 'Eicosadienoic',
			'eicosatrienoic' => 'Eicosatrienoic',
			'eicosatetraenoic' => 'Eicosatetraenoic',
			'eicosapentaenoic' => 'Eicosapentaenoic',
			'docosapentaenoic' => 'Docosapentaenoic',
			'docosahexaenoic' => 'Docosahexaenoic',
			'transOctadecenoic' => 'Trans Octadecenoic',
			'transOctadecadienoic' => 'Trans Octadecadienoic',
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

		$criteria->compare('tacoId',$this->tacoId);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('scientificName',$this->scientificName,true);
		$criteria->compare('protein',$this->protein);
		$criteria->compare('lipids',$this->lipids);
		$criteria->compare('carbohydrate',$this->carbohydrate);
		$criteria->compare('ashes',$this->ashes);
		$criteria->compare('energyKcal',$this->energyKcal);
		$criteria->compare('energyKj',$this->energyKj);
		$criteria->compare('moisture',$this->moisture);
		$criteria->compare('fiber',$this->fiber);
		$criteria->compare('mineralsCalcium',$this->mineralsCalcium);
		$criteria->compare('mineralsIron',$this->mineralsIron);
		$criteria->compare('mineralsMagnesium',$this->mineralsMagnesium);
		$criteria->compare('mineralsPhosphorus',$this->mineralsPhosphorus);
		$criteria->compare('mineralsPotassium',$this->mineralsPotassium);
		$criteria->compare('mineralsSodium',$this->mineralsSodium);
		$criteria->compare('mineralsZinc',$this->mineralsZinc);
		$criteria->compare('mineralsCopper',$this->mineralsCopper);
		$criteria->compare('mineralsManganese',$this->mineralsManganese);
		$criteria->compare('vitaminsRetinol',$this->vitaminsRetinol);
		$criteria->compare('vitaminsRE',$this->vitaminsRE);
		$criteria->compare('vitaminsRAE',$this->vitaminsRAE);
		$criteria->compare('vitaminsC',$this->vitaminsC);
		$criteria->compare('vitaminsThiamine',$this->vitaminsThiamine);
		$criteria->compare('vitaminsRiboflavin',$this->vitaminsRiboflavin);
		$criteria->compare('vitaminsNiacin',$this->vitaminsNiacin);
		$criteria->compare('vitaminsB6',$this->vitaminsB6);
		$criteria->compare('aminoacidsAlanine',$this->aminoacidsAlanine);
		$criteria->compare('aminoacidsArginine',$this->aminoacidsArginine);
		$criteria->compare('aminoacidsAspartic',$this->aminoacidsAspartic);
		$criteria->compare('aminoacidsCystine',$this->aminoacidsCystine);
		$criteria->compare('aminoacidsGlutamic',$this->aminoacidsGlutamic);
		$criteria->compare('aminoacidsGlycine',$this->aminoacidsGlycine);
		$criteria->compare('aminoacidsHistidine',$this->aminoacidsHistidine);
		$criteria->compare('aminoacidsIsoleucine',$this->aminoacidsIsoleucine);
		$criteria->compare('aminoacidsLeucine',$this->aminoacidsLeucine);
		$criteria->compare('aminoacidsLysine',$this->aminoacidsLysine);
		$criteria->compare('aminoacidsMethionine',$this->aminoacidsMethionine);
		$criteria->compare('aminoacidsPhenylalanine',$this->aminoacidsPhenylalanine);
		$criteria->compare('aminoacidsProline',$this->aminoacidsProline);
		$criteria->compare('aminoacidsSerine',$this->aminoacidsSerine);
		$criteria->compare('aminoacidsThreonine',$this->aminoacidsThreonine);
		$criteria->compare('aminoacidsTryptophan',$this->aminoacidsTryptophan);
		$criteria->compare('aminoacidsTyrosine',$this->aminoacidsTyrosine);
		$criteria->compare('aminoacidsValine',$this->aminoacidsValine);
		$criteria->compare('butanoic',$this->butanoic);
		$criteria->compare('hexanoic',$this->hexanoic);
		$criteria->compare('octanoic',$this->octanoic);
		$criteria->compare('decanoic',$this->decanoic);
		$criteria->compare('dodecanoic',$this->dodecanoic);
		$criteria->compare('tetracanoic',$this->tetracanoic);
		$criteria->compare('hexadecanoic',$this->hexadecanoic);
		$criteria->compare('octadecanoic',$this->octadecanoic);
		$criteria->compare('eicosanoic',$this->eicosanoic);
		$criteria->compare('docosanoic',$this->docosanoic);
		$criteria->compare('tetracosanoic',$this->tetracosanoic);
		$criteria->compare('tetracenoic',$this->tetracenoic);
		$criteria->compare('hexadecenoic',$this->hexadecenoic);
		$criteria->compare('octadecenoic',$this->octadecenoic);
		$criteria->compare('eicosenoic',$this->eicosenoic);
		$criteria->compare('docosenoic',$this->docosenoic);
		$criteria->compare('tetracosenoic',$this->tetracosenoic);
		$criteria->compare('octadecadienoic',$this->octadecadienoic);
		$criteria->compare('octadecatrienoic',$this->octadecatrienoic);
		$criteria->compare('octadecatetraenoic',$this->octadecatetraenoic);
		$criteria->compare('eicosadienoic',$this->eicosadienoic);
		$criteria->compare('eicosatrienoic',$this->eicosatrienoic);
		$criteria->compare('eicosatetraenoic',$this->eicosatetraenoic);
		$criteria->compare('eicosapentaenoic',$this->eicosapentaenoic);
		$criteria->compare('docosapentaenoic',$this->docosapentaenoic);
		$criteria->compare('docosahexaenoic',$this->docosahexaenoic);
		$criteria->compare('transOctadecenoic',$this->transOctadecenoic);
		$criteria->compare('transOctadecadienoic',$this->transOctadecadienoic);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Food the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
