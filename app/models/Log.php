<?php

/**
 * This is the model class for table "log".
 *
 * The followings are the available columns in table 'log':
 * @property integer $id
 * @property string $reference
 * @property string $reference_ids
 * @property string $crud
 * @property string $date
 * @property string $additional_info
 * @property string $school_fk
 * @property integer $user_fk
 *
 * The followings are the available model relations:
 * @property SchoolIdentification $schoolFk
 * @property Users $userFk
 */
class Log extends TagModel
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'log';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('reference, reference_ids, crud, school_fk, user_fk', 'required'),
            array('user_fk', 'numerical', 'integerOnly' => true),
            array('reference', 'length', 'max' => 50),
            array('reference_ids', 'length', 'max' => 20),
            array('crud', 'length', 'max' => 1),
            array('school_fk', 'length', 'max' => 8),
            array('date', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, reference, reference_ids, crud, date, additional_info, school_fk, user_fk', 'safe', 'on' => 'search'),
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
            'id' => 'ID',
            'reference' => 'Reference',
            'reference_ids' => 'Reference Ids',
            'crud' => 'Crud',
            'date' => 'Date',
            'additional_info' => 'Additional Info',
            'school_fk' => 'School Fk',
            'user_fk' => 'User Fk',
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

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('reference', $this->reference, true);
        $criteria->compare('reference_ids', $this->reference_ids, true);
        $criteria->compare('crud', $this->crud, true);
        $criteria->compare('date', $this->date, true);
        $criteria->compare('additional_info', $this->additional_info, true);
        $criteria->compare('school_fk', $this->school_fk, true);
        $criteria->compare('user_fk', $this->user_fk);

        return new CActiveDataProvider(
            $this,
            array(
                'criteria' => $criteria,
            )
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Log the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public static function saveAction($reference, $referenceIds, $crud, $additionalInfo = null)
    {
        $date = new DateTime();
        $log = new Log();
        $log->reference = $reference;
        $log->reference_ids = $referenceIds;
        $log->crud = $crud;
        $log->date = $date->format("Y-m-d H:i:s");
        $log->user_fk = Yii::app()->user->loginInfos->id;
        $log->school_fk = Yii::app()->user->school;
        $log->additional_info = $additionalInfo;
        $log->save();
    }

    public static function loadIconsAndTexts($log)
    {
        $text = $icon = $color = $crud = "";

        switch ($log->crud) {
            case "C":
                $crud = "criado(a)";
                $color = "lightgreen";
                break;
            case "U":
                $crud = "atualizado(a)";
                $color = "lightskyblue";
                break;
            case "E":
                $crud = "executado";
                $color = "lightgreen";
                break;
            case "D":
                $crud = "excluído(a)";
                $color = "lightcoral";
                break;
        }

        switch ($log->reference) {
            case "class":
                $infos = explode("|", $log->additional_info);
                $text = 'As aulas ministradas da turma "' . $infos[0] . '" de ' . $infos[1] . ' do mês de ' . strtolower($infos[2]) . ' foram atualizadas.';
                $icon = "aulas_ministradas";
                break;
            case "frequency":
                $infos = explode("|", $log->additional_info);
                $text = 'A frequência da turma "' . $infos[0] . '" de ' . $infos[1] . ' do mês de ' . strtolower($infos[2]) . ' foi atualizada.';
                $icon = "frequencia";
                break;
            case "classroom":
                $text = 'Turma "' . $log->additional_info . '" foi ' . $crud . ".";
                $icon = "turmas";
                break;
            case "courseplan":
                $text = 'Plano de aula "' . $log->additional_info . '" foi ' . $crud . ".";
                $icon = "plano_de_aula";
                break;
            case "enrollment":
                $infos = explode("|", $log->additional_info);
                $text = '"' . $infos[0] . '" foi ' . $crud . ' na turma "' . $infos[1] . '".';
                $icon = "matricula";
                break;
            case "instructor":
                $text = 'Professor(a) "' . $log->additional_info . '" foi ' . $crud . ".";
                $icon = "professores";
                break;
            case "professional":
                $text = 'Profissional "' . $log->additional_info . '" foi ' . $crud . ".";
                $icon = "professional";
                break;
            case "school":
                $text = 'Escola "' . $log->additional_info . '" foi ' . $crud . ".";
                $icon = "escola";
                break;
            case "student":
                $text = 'Aluno(a) "' . $log->additional_info . '" foi ' . $crud . ".";
                $icon = "alunos";
                break;
            case "grade":
                $text = 'As notas da turma "' . $log->additional_info . '" foram ' . $crud . ".";
                $icon = "notas";
                break;
            case "calendar":
                $text = 'Calendário de ' . $log->additional_info . ' foi ' . $crud . ".";
                $icon = "calendario";
                break;
            case "curricular_matrix":
                $infos = explode("|", $log->additional_info);
                $text = 'Matriz curricular do componente curricular/eixo "' . $infos[1] . '" da etapa "' . $infos[0] . '" foi ' . $crud . ".";
                $icon = "matriz_curricular";
                break;
            case "lunch_stock":
                $infos = explode("|", $log->additional_info);
                if ($log->crud == "C") {
                    $text = $infos[1] . ' de ' . $infos[0] . ' foram adicionados ao estoque.';
                    $icon = "adicionar-igrediente";
                } else {
                    $text = $infos[1] . ' de ' . $infos[0] . ' foram removidos do estoque.';
                    $icon = "remover-igrediente";
                }
                break;
            case "lunch_menu":
                $text = 'Cardápio "' . $log->additional_info . '" foi ' . $crud . ".";
                $icon = "cardapio";
                break;
            case "lunch_meal":
                $text = 'Uma refeição foi ' . $crud . ' no cardápio "' . $log->additional_info . '".';
                $icon = "merenda";
                break;
            case "timesheet":
                $text = 'Quadro de Horário da turma "' . $log->additional_info . '" foi gerado.';
                $icon = "quadro_de_horario";
                break;
            case "wizard_classroom":
                $text = 'Turmas de ' . $log->additional_info . ' foram reaproveitadas.';
                $icon = "turmas";
                break;
            case "wizard_student":
                $text = 'Alunos de ' . $log->additional_info . ' foram rematriculados.';
                $icon = "alunos";
                break;
            case "educacenso":
                $text = 'Arquivo do censo da unidade escolar ' . $log->additional_info . ' foi exportado!';
                $icon = "escola";
                break;
            case "foodMenu":
                $text = 'O Cardápio "' . $log->additional_info . '" foi adicionado.';
                $icon = "cardapio";
        }
        return [
            "text" => $text,
            "icon" => $icon,
            "color" => $color
        ];
    }
}
