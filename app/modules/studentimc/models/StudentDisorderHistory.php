<?php

/**
 * This is the model class for table "student_disorder_history".
 *
 * The followings are the available columns in table 'student_disorder_history':
 * @property integer $id
 * @property integer $student_disorder_fk
 * @property integer $student_imc_fk
 * @property integer $student_fk
 * @property integer $tdah
 * @property integer $depressao
 * @property integer $tab
 * @property integer $toc
 * @property integer $tag
 * @property integer $tod
 * @property integer $tcne
 * @property string $others
 * @property string $created_at
 * @property string $updated_at
 * @property integer $disorders_impact_learning
 * @property integer $dyscalculia
 * @property integer $dysgraphia
 * @property integer $dyslalia
 * @property integer $dyslexia
 * @property integer $tpac
 * @property integer $iron_deficiency_anemia
 * @property integer $hypovitaminosis_a
 * @property integer $rickets
 * @property integer $scurvy
 * @property integer $iodine_deficiency
 * @property integer $protein_energy_malnutrition
 * @property integer $overweight
 * @property integer $obesity
 * @property integer $dyslipidemia
 * @property integer $hyperglycemia_prediabetes
 * @property integer $type2_diabetes_mellitus
 * @property integer $anorexia_nervosa
 * @property integer $bulimia_nervosa
 * @property integer $binge_eating_disorder
 * @property integer $lactose_intolerance
 * @property integer $celiac_disease
 * @property integer $food_allergies
 * @property integer $asthma
 * @property integer $chronic_bronchitis
 * @property integer $allergic_rhinitis
 * @property integer $chronic_sinusitis
 * @property integer $diabetes_mellitus
 * @property integer $hypothyroidism
 * @property integer $hyperthyroidism
 * @property integer $dyslipidemia_metabolic
 * @property integer $arterial_hypertension
 * @property integer $congenital_heart_disease
 * @property integer $chronic_gastritis
 * @property integer $gastroesophageal_reflux_disease
 * @property integer $epilepsy
 *
 * The followings are the available model relations:
 * @property StudentDisorder $studentDisorderFk
 * @property StudentImc $studentImcFk
 */
class StudentDisorderHistory extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'student_disorder_history';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('student_fk', 'required'),
			array('student_disorder_fk, student_imc_fk, student_fk, tdah, depressao, tab, toc, tag, tod, tcne, disorders_impact_learning, dyscalculia, dysgraphia, dyslalia, dyslexia, tpac, iron_deficiency_anemia, hypovitaminosis_a, rickets, scurvy, iodine_deficiency, protein_energy_malnutrition, overweight, obesity, dyslipidemia, hyperglycemia_prediabetes, type2_diabetes_mellitus, anorexia_nervosa, bulimia_nervosa, binge_eating_disorder, lactose_intolerance, celiac_disease, food_allergies, asthma, chronic_bronchitis, allergic_rhinitis, chronic_sinusitis, diabetes_mellitus, hypothyroidism, hyperthyroidism, dyslipidemia_metabolic, arterial_hypertension, congenital_heart_disease, chronic_gastritis, gastroesophageal_reflux_disease, epilepsy', 'numerical', 'integerOnly'=>true),
			array('others', 'length', 'max'=>200),
			array('created_at, updated_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, student_disorder_fk, student_imc_fk, student_fk, tdah, depressao, tab, toc, tag, tod, tcne, others, created_at, updated_at, disorders_impact_learning, dyscalculia, dysgraphia, dyslalia, dyslexia, tpac, iron_deficiency_anemia, hypovitaminosis_a, rickets, scurvy, iodine_deficiency, protein_energy_malnutrition, overweight, obesity, dyslipidemia, hyperglycemia_prediabetes, type2_diabetes_mellitus, anorexia_nervosa, bulimia_nervosa, binge_eating_disorder, lactose_intolerance, celiac_disease, food_allergies, asthma, chronic_bronchitis, allergic_rhinitis, chronic_sinusitis, diabetes_mellitus, hypothyroidism, hyperthyroidism, dyslipidemia_metabolic, arterial_hypertension, congenital_heart_disease, chronic_gastritis, gastroesophageal_reflux_disease, epilepsy', 'safe', 'on'=>'search'),
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
			'studentDisorderFk' => array(self::BELONGS_TO, 'StudentDisorder', 'student_disorder_fk'),
			'studentImcFk' => array(self::BELONGS_TO, 'StudentImc', 'student_imc_fk'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'student_fk' => 'Student Fk',
            'tdah' => 'Tdah',
            'depressao' => 'Depressao',
            'tab' => 'Tab',
            'toc' => 'Toc',
            'tag' => 'Tag',
            'tod' => 'Tod',
            'tcne' => 'Tcne',
            'others' => 'Others',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'disorders_impact_learning' => 'Transtorno(s) que impacta(m) o desenvolvimento da aprendizagem',
            'dyscalculia' => 'Discalculia',
            'dysgraphia' => 'Disgrafia',
            'dyslalia' => 'Dislalia',
            'dyslexia' => 'Dislexia',
            'tpac' => 'Tpac',
            'iron_deficiency_anemia' => 'Anemia ferropriva (falta de ferro)',
            'hypovitaminosis_a' => 'Hipovitaminose A (deficiência de vitamina A)',
            'rickets' => 'Raquitismo (deficiência de vitamina D e cálcio)',
            'scurvy' => 'Escorbuto (deficiência de vitamina C)',
            'iodine_deficiency' => 'Deficiência de iodo',
            'protein_energy_malnutrition' => 'Desnutrição energético-proteica',
            'overweight' => 'Sobrepeso',
            'obesity' => 'Obesidade',
            'dyslipidemia' => 'Dislipidemia (colesterol e triglicerídeos altos)',
            'hyperglycemia_prediabetes' => 'Hiperglicemia / Pré-diabetes',
            'type2_diabetes_mellitus' => 'Diabetes mellitus tipo 2',
            'anorexia_nervosa' => 'Anorexia Nervosa',
            'bulimia_nervosa' => 'Bulimia Nervosa',
            'binge_eating_disorder' => 'Transtorno da compulsão alimentar periódica',
            'lactose_intolerance' => 'Intolerância à lactose',
            'celiac_disease' => 'Doença celíaca (intolerância ao glúten)',
            'food_allergies' => 'Alergias alimentares (leite, ovo, peixe, amendoim, etc.)',
            'asthma' => 'Asma',
            'chronic_bronchitis' => 'Bronquite crônicaBronquite crônica',
            'allergic_rhinitis' => 'Rinite alérgica',
            'chronic_sinusitis' => 'Sinusite crônica',
            'diabetes_mellitus' => 'Diabetes mellitus (Tipo 1 ou Tipo 2)',
            'hypothyroidism' => 'Hipotireoidismo',
            'hyperthyroidism' => 'Hipertireoidismo',
            'dyslipidemia_metabolic' => 'Dislipidemia (colesterol/triglicerídeos altos)',
            'arterial_hypertension' => 'Hipertensão arterial',
            'congenital_heart_disease' => 'Cardiopatias congênitas ou adquiridas',
            'chronic_gastritis' => 'Gastrite crônica',
            'gastroesophageal_reflux_disease' => 'Refluxo gastroesofágico',
            'epilepsy' => 'Epilepsia',
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
		$criteria->compare('student_disorder_fk',$this->student_disorder_fk);
		$criteria->compare('student_imc_fk',$this->student_imc_fk);
		$criteria->compare('student_fk',$this->student_fk);
		$criteria->compare('tdah',$this->tdah);
		$criteria->compare('depressao',$this->depressao);
		$criteria->compare('tab',$this->tab);
		$criteria->compare('toc',$this->toc);
		$criteria->compare('tag',$this->tag);
		$criteria->compare('tod',$this->tod);
		$criteria->compare('tcne',$this->tcne);
		$criteria->compare('others',$this->others,true);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('updated_at',$this->updated_at,true);
		$criteria->compare('disorders_impact_learning',$this->disorders_impact_learning);
		$criteria->compare('dyscalculia',$this->dyscalculia);
		$criteria->compare('dysgraphia',$this->dysgraphia);
		$criteria->compare('dyslalia',$this->dyslalia);
		$criteria->compare('dyslexia',$this->dyslexia);
		$criteria->compare('tpac',$this->tpac);
		$criteria->compare('iron_deficiency_anemia',$this->iron_deficiency_anemia);
		$criteria->compare('hypovitaminosis_a',$this->hypovitaminosis_a);
		$criteria->compare('rickets',$this->rickets);
		$criteria->compare('scurvy',$this->scurvy);
		$criteria->compare('iodine_deficiency',$this->iodine_deficiency);
		$criteria->compare('protein_energy_malnutrition',$this->protein_energy_malnutrition);
		$criteria->compare('overweight',$this->overweight);
		$criteria->compare('obesity',$this->obesity);
		$criteria->compare('dyslipidemia',$this->dyslipidemia);
		$criteria->compare('hyperglycemia_prediabetes',$this->hyperglycemia_prediabetes);
		$criteria->compare('type2_diabetes_mellitus',$this->type2_diabetes_mellitus);
		$criteria->compare('anorexia_nervosa',$this->anorexia_nervosa);
		$criteria->compare('bulimia_nervosa',$this->bulimia_nervosa);
		$criteria->compare('binge_eating_disorder',$this->binge_eating_disorder);
		$criteria->compare('lactose_intolerance',$this->lactose_intolerance);
		$criteria->compare('celiac_disease',$this->celiac_disease);
		$criteria->compare('food_allergies',$this->food_allergies);
		$criteria->compare('asthma',$this->asthma);
		$criteria->compare('chronic_bronchitis',$this->chronic_bronchitis);
		$criteria->compare('allergic_rhinitis',$this->allergic_rhinitis);
		$criteria->compare('chronic_sinusitis',$this->chronic_sinusitis);
		$criteria->compare('diabetes_mellitus',$this->diabetes_mellitus);
		$criteria->compare('hypothyroidism',$this->hypothyroidism);
		$criteria->compare('hyperthyroidism',$this->hyperthyroidism);
		$criteria->compare('dyslipidemia_metabolic',$this->dyslipidemia_metabolic);
		$criteria->compare('arterial_hypertension',$this->arterial_hypertension);
		$criteria->compare('congenital_heart_disease',$this->congenital_heart_disease);
		$criteria->compare('chronic_gastritis',$this->chronic_gastritis);
		$criteria->compare('gastroesophageal_reflux_disease',$this->gastroesophageal_reflux_disease);
		$criteria->compare('epilepsy',$this->epilepsy);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return StudentDisorderHistory the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
