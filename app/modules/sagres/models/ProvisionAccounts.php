<?php

class ProvisionAccounts extends CActiveRecord
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'provision_accounts';
    }

    public function rules()
    {
        return array(
            array('codigounidgestora, nomeunidgestora, cpfcontador, cpfgestor, anoreferencia, mesreferencia, versaoxml, diainicprescontas, diafinaprescontas', 'required'),
            array('anoreferencia, mesreferencia, versaoxml, diainicprescontas, diafinaprescontas', 'numerical', 'integerOnly' => true),
            array('codigounidgestora', 'length', 'max' => 30),
            array('nomeunidgestora', 'length', 'max' => 150),
            array('cpfcontador, cpfgestor', 'length', 'max' => 16),
            array('codigounidgestora, nomeunidgestora, cpfcontador, cpfgestor, anoreferencia, mesreferencia, versaoxml, diainicprescontas, diafinaprescontas', 'safe', 'on' => 'search'),
        );
    }

    public function relations()
    {
        return array(
        );
    }

    public function attributeLabels()
    {
        return array(
            'codigounidgestora' => 'Codigo Unidade Gestora',
            'nomeunidgestora' => 'Nome Unidade Gestora',
            'cpfcontador' => 'CPF Contador',
            'cpfgestor' => 'CPF Gestor',
            'anoreferencia' => 'Ano Referencia',
            'mesreferencia' => 'Mes Referencia',
            'versaoxml' => 'Versao XML',
            'diainicprescontas' => 'Inicio Apresentacao de Contas',
            'diafinaprescontas' => 'Fim Apresentacao de Contas',
        );
    }

    public function search()
    {
        $criteria = new CDbCriteria;

        $criteria->compare('codigounidgestora', $this->codigounidgestora, true);
        $criteria->compare('nomeunidgestora', $this->nomeunidgestora, true);
        $criteria->compare('cpfcontador', $this->cpfcontador, true);
        $criteria->compare('cpfgestor', $this->cpfgestor, true);
        $criteria->compare('anoreferencia', $this->anoreferencia);
        $criteria->compare('mesreferencia', $this->mesreferencia);
        $criteria->compare('versaoxml', $this->versaoxml);
        $criteria->compare('diainicprescontas', $this->diainicprescontas);
        $criteria->compare('diafinaprescontas', $this->diafinaprescontas);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        )
        );
    }

}