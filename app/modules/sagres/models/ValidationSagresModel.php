<?php

/**
 * @property int $id
 * @property string $enrollment
 * @property string $school;
 * @property string $description;
 * @property string $action;
 * @property string $inep_id;
 * @property string $idSchool;
 * @property int $idClass;
 * @property int $identifier;
 * @property int $idStudent;
 * @property int $idLunch
 *
 */
class ValidationSagresModel extends TagModel
{
    public $idClass;
    public $idSchool;
    public $identifier;
    public $idProfessional;
    public $idStudent;

    public function tableName()
    {
        return 'inconsistency_sagres';
    }

    public function rules()
    {
        return [
            ['enrollment, school, description, action', 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'enrollment' => 'Cadastro',
            'school' => 'Escola',
            'description' => 'Descrição',
            'action' => 'Ação',
            'idSchool' => 'School Id',
            'idClass' => 'Class Id',
            'identifier' => 'Identifier',
        ];
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name
     * @return ValidationSagresModel the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
