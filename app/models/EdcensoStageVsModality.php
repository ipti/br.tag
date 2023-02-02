<?php

    /**
     * This is the model class for table "edcenso_stage_vs_modality".
     *
     * The followings are the available columns in table 'edcenso_stage_vs_modality':
     * @property integer $id
     * @property string $name
     * @property integer $stage
     *
     * The followings are the available model relations:
     * @property SchoolStagesConceptGrades[] $schoolStagesConceptGrades
     * @property StudentEnrollment[] $studentEnrollments
     */
    class EdcensoStageVsModality extends CActiveRecord
    {
        /**
         * Returns the static model of the specified AR class.
         * @param string $className active record class name.
         * @return EdcensoStageVsModality the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }

        /**
         *  Gets all stages possibles
         *
         * @return EdcensoStageVsModality[]
         */
        public static function getAll()
        {
            $criteria = new CDbCriteria();
            $criteria->addInCondition('id', EdcensoStageVsModality::getNextStages(-1));
            return EdcensoStageVsModality::model()->findAll($criteria);
        }

        public static function getNextStages($id)
        {
            $ids = [];
            switch ($id) {
                case 1:
                case 2:
                case 3:
                    $ids = [1, 2, 3, 14, 22, 23];
                    break;
                case 14:
                    $ids = [14, 15, 22, 23];
                    break;
                case 15:
                    $ids = [15, 16, 22, 23];
                    break;
                case 16:
                    $ids = [16, 17, 22, 23];
                    break;
                case 17:
                    $ids = [17, 18, 22, 23];
                    break;
                case 18:
                    $ids = [18, 19, 22, 23];
                    break;
                case 19:
                    $ids = [19, 20, 22, 23];
                    break;
                case 20:
                    $ids = [20, 21, 22, 23];
                    break;
                case 21:
                    $ids = [21, 41, 22, 23];
                    break;
                case 41:
                    $ids = [41, 25];
                    break;
                case 25:
                    $ids = [25, 26];
                    break;
                case 26:
                    $ids = [26, 27];
                    break;
                case 27:
                    $ids = [27, 28];
                    break;
                case 28:
                    $ids = [28];
                    break;
                case 43:
                    $ids = [43, 44];
                    break;
                case 44:
                    $ids = [44];
                    break;
                default:
                    $ids = [1, 2, 3, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 25, 26, 27, 28, 41, 43, 44];
                    break;
            }

            return $ids;
        }

        /**
         * @return string the associated database table name
         */
        public function tableName()
        {
            return 'edcenso_stage_vs_modality';
        }

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return [
                ['name, stage', 'required'], ['stage', 'numerical', 'integerOnly' => true],
                ['name', 'length', 'max' => 100], // The following rule is used by search().
                // Please remove those attributes that should not be searched.
                ['id, name, stage', 'safe', 'on' => 'search'],
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
                'schoolStagesConceptGrades' => [self::HAS_MANY, 'SchoolStagesConceptGrades', 'edcenso_stage_vs_modality_fk'],
                'studentEnrollments' => [self::HAS_MANY, 'StudentEnrollment', 'edcenso_stage_vs_modality_fk'],
            ];
        }

        /**
         * @return array customized attribute labels (name=>label)
         */
        public function attributeLabels()
        {
            return [
                'id' => Yii::t('default', 'ID'), 'name' => Yii::t('default', 'Name'),
                'stage' => Yii::t('default', 'Stage'),
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
            $criteria->compare('name', $this->name, true);
            $criteria->compare('stage', $this->stage);

            return new CActiveDataProvider($this, [
                'criteria' => $criteria,
            ]);
        }
    }
