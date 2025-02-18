<?php

/**
 * This is the model class for table "student".
 *
 * The followings are the available columns in table 'student':
 * @property string $register_type
 * @property string $school_inep_id_fk
 * @property string $inep_id
 * @property integer $id
 * @property string $name
 * @property string $civil_name
 * @property string $email
 * @property string $nis
 * @property string $birthday_date
 * @property integer $sex
 * @property integer $color_race
 * @property boolean $filiation
 * @property string $filiation_1
 * @property string $filiation_2
 * @property integer $nationality
 * @property integer $edcenso_nation_fk
 * @property integer $edcenso_uf_fk
 * @property integer $edcenso_city_fk
 * @property boolean $deficiency
 * @property boolean $deficiency_type_blindness
 * @property boolean $deficiency_type_low_vision
 * @property boolean $deficiency_type_monocular_vision
 * @property boolean $deficiency_type_deafness
 * @property boolean $deficiency_type_disability_hearing
 * @property boolean $deficiency_type_deafblindness
 * @property boolean $deficiency_type_phisical_disability
 * @property boolean $deficiency_type_intelectual_disability
 * @property boolean $deficiency_type_multiple_disabilities
 * @property boolean $deficiency_type_autism
 * @property boolean $deficiency_type_gifted
 * @property integer $hash
 * @property integer $users_fk
 *
 * The followings are the available model relations:
 * @property ClassBoard[] $classBoards
 * @property InstructorDisciplines[] $instructorDisciplines
 * @property EdcensoNation $edcensoNationFk
 * @property EdcensoUf $edcensoUfFk
 * @property EdcensoCity $edcensoCityFk
 * @property Users $usersFk
 * @property InstructorSchool[] $instructorSchools
 * @property InstructorTeachingData[] $instructorTeachingDatas
 * @property Schedule[] $schedules
 * @property Unavailability[] $unavailabilities
 * @property InstructorDocumentsAndAddress $documents
 * @property InstructorVariableData $instructorVariableData
 */
class InstructorIdentification extends AltActiveRecord
{
    public const SCENARIO_IMPORT = "SCENARIO_IMPORT";
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return InstructorIdentification the static model class
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
        return 'instructor_identification';
    }


    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('school_inep_id_fk, name, birthday_date, sex, color_race, nationality, edcenso_nation_fk, deficiency, filiation', 'required'),
            array('sex, color_race, filiation, nationality, edcenso_nation_fk, edcenso_uf_fk, edcenso_city_fk, deficiency, deficiency_type_blindness, deficiency_type_low_vision, deficiency_type_monocular_vision, deficiency_type_deafness, deficiency_type_disability_hearing, deficiency_type_deafblindness, deficiency_type_phisical_disability, deficiency_type_intelectual_disability, deficiency_type_multiple_disabilities, deficiency_type_autism, deficiency_type_gifted, users_fk', 'numerical', 'integerOnly'=>true),
            array('register_type', 'length', 'max'=>2),
            array('school_inep_id_fk', 'length', 'max'=>8),
            array('inep_id', 'length', 'max'=>12),
            array('name, civil_name, email, filiation_1, filiation_2', 'length', 'max'=>100),
            array('nis', 'length', 'max'=>11),
            array('birthday_date', 'length', 'max'=>10),
            array('hash', 'length', 'max'=>40),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('register_type, school_inep_id_fk, inep_id, id, name, civil_name, email, nis, birthday_date, sex, color_race, filiation, filiation_1, filiation_2, nationality, edcenso_nation_fk, edcenso_uf_fk, edcenso_city_fk, deficiency, deficiency_type_blindness, deficiency_type_low_vision, deficiency_type_monocular_vision, deficiency_type_deafness, deficiency_type_disability_hearing, deficiency_type_deafblindness, deficiency_type_phisical_disability, deficiency_type_intelectual_disability, deficiency_type_multiple_disabilities, hash, users_fk', 'safe', 'on'=>'search'),
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
            'edcensoNationFk' => array(self::BELONGS_TO, 'EdcensoNation', 'edcenso_nation_fk'),
            'edcensoUfFk' => array(self::BELONGS_TO, 'EdcensoUf', 'edcenso_uf_fk'),
            'edcensoCityFk' => array(self::BELONGS_TO, 'EdcensoCity', 'edcenso_city_fk'),
            'usersFk' => array(self::BELONGS_TO, 'Users', 'users_fk'),
            'documents' => array(self::HAS_ONE, 'InstructorDocumentsAndAddress', 'id'),
            'instructorVariableData' => array(self::HAS_ONE, 'InstructorVariableData', 'id'),
            'instructorTeachingDatas' => array(self::HAS_MANY, 'InstructorTeachingData', 'instructor_fk'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'register_type' => Yii::t('default', 'Register Type'),
            'school_inep_id_fk' => Yii::t('default', 'School Inep Id Fk'),
            'inep_id' => Yii::t('default', 'Inep'),
            'id' => Yii::t('default', 'ID'),
            'name' => Yii::t('default', 'Nome de Apresentação'),
            'civil_name' => Yii::t('default', 'Nome civil'),
            'email' => Yii::t('default', 'Email'),
            'nis' => Yii::t('default', 'Nis'),
            'birthday_date' => Yii::t('default', 'Birthday Date'),
            'sex' => Yii::t('default', 'Sex'),
            'color_race' => Yii::t('default', 'Color Race'),
            'filiation' => Yii::t('default', 'Filiation'),
            'filiation_1' => Yii::t('default', 'Filiation 1'),
            'filiation_2' => Yii::t('default', 'Filiation 2'),
            'nationality' => Yii::t('default', 'Nationality'),
            'edcenso_nation_fk' => Yii::t('default', 'Edcenso Nation Fk'),
            'edcenso_uf_fk' => Yii::t('default', 'Edcenso Uf Fk'),
            'edcenso_city_fk' => Yii::t('default', 'Edcenso City Fk'),
            'deficiency' => Yii::t('default', 'Deficiency'),
            'deficiency_type_blindness' => Yii::t('default', 'Deficiency Type Blindness'),
            'deficiency_type_low_vision' => Yii::t('default', 'Deficiency Type Low Vision'),
            'deficiency_type_monocular_vision' => Yii::t('default', 'Deficiency Type Monocular Vision'),
            'deficiency_type_deafness' => Yii::t('default', 'Deficiency Type Deafness'),
            'deficiency_type_disability_hearing' => Yii::t('default', 'Deficiency Type Disability Hearing'),
            'deficiency_type_deafblindness' => Yii::t('default', 'Deficiency Type Deafblindness'),
            'deficiency_type_phisical_disability' => Yii::t('default', 'Deficiency Type Phisical Disability'),
            'deficiency_type_intelectual_disability' => Yii::t('default', 'Deficiency Type Intelectual Disability'),
            'deficiency_type_multiple_disabilities' => Yii::t('default', 'Deficiency Type Multiple Disabilities'),
            'role' => Yii::t('default', 'Role'),
            'deficiency_type_autism' => Yii::t('default', 'Deficiency Type Autism'),
            'deficiency_type_gifted' => Yii::t('default', 'Deficiency Type Gifted'),
            'users_fk' => 'Users Fk',
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

        $criteria = new CDbCriteria();
        //$criteria->with = array('documents');

        $criteria->compare('register_type', $this->register_type, true);

        // if (Yii::app()->getAuthManager()->checkAccess('manager', Yii::app()->user->loginInfos->id)) {
        //     $school = Yii::app()->user->school;
        //     $criteria->compare('school_inep_id_fk', $school);
        // }

        $criteria->compare('inep_id', $this->inep_id, true);
        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                    'sort' => array(
                        'defaultOrder' => array(
                            'name' => CSort::SORT_ASC
                        ),
                    ),
                    'pagination' => false
                ));
    }

    public function getDisciplines()
    {

        $disciplines = EdcensoDiscipline::model()
        ->with(array(
                'curricularMatrixes.teachingMatrixes.teachingDataFk' => array(
                'condition' => 'teachingDataFk.instructor_fk=:instructor_fk',
                'params' => array(':instructor_fk' => $this->id),
            )
        ))
        ->findAll(array(
            'select' => 'DISTINCT ed.*',
        ));

        return $disciplines;
    }

    public function getClassrooms()
    {
        $instructorId = $this->id;

        $sql = "SELECT c.id as cid, ii.name as instructor_name, c.name as classroom_name, c.school_year as syear, si.name as school_name
        from instructor_teaching_data itd
        join instructor_identification ii on itd.instructor_fk = ii.id
        join classroom c on c.id = itd.classroom_id_fk
        join school_identification si on si.inep_id = c.school_inep_fk
        WHERE ii.id = :intructorId
        ORDER BY ii.name;";

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':intructorId', $instructorId, PDO::PARAM_INT);


        $classrooms = $command->queryAll();

        return $classrooms;
    }

}
