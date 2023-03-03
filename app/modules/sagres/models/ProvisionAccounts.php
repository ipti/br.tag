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
            array('codigoUnidGestora, nomeUnidGestora, cpfResponsavel, cpfGestor, anoReferencia, mesReferencia, versaoxml, diaInicPresContas, diaFinaPresContas', 'required'),
            array('anoReferencia, mesReferencia, versaoxml, diaInicPresContas, diaFinaPresContas', 'numerical', 'integerOnly' => true),
            array('codigoUnidGestora', 'length', 'max' => 30),
            array('nomeUnidGestora', 'length', 'max' => 150),
            array('cpfResponsavel, cpfGestor', 'length', 'max' => 16),
            array('codigoUnidGestora, nomeUnidGestora, cpfResponsavel, cpfGestor, anoReferencia, mesReferencia, versaoxml, diaInicPresContas, diaFinaPresContas', 'safe', 'on' => 'search'),
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
            'codigoUnidGestora' => 'Codigo da Unidade Gestora',
            'nomeUnidGestora' => 'Nome da Unidade Gestora',
            'cpfResponsavel' => 'CPF do Responsável',
            'cpfGestor' => 'CPF do Gestor',
            'anoReferencia' => 'Ano de Referencia',
            'mesReferencia' => 'Mes de Referencia',
            'versaoxml' => 'Versao do XML',
            'diaInicPresContas' => 'Inicio Apresentacao de Contas',
            'diaFinaPresContas' => 'Fim Apresentacao de Contas',
        );
    }

    public function search()
    {
        $criteria = new CDbCriteria;

        $criteria->compare('codigoUnidGestora', $this->codigoUnidGestora, true);
        $criteria->compare('nomeUnidGestora', $this->nomeUnidGestora, true);
        $criteria->compare('cpfResponsavel', $this->cpfResponsavel, true);
        $criteria->compare('cpfGestor', $this->cpfGestor, true);
        $criteria->compare('anoReferencia', $this->anoReferencia);
        $criteria->compare('mesReferencia', $this->mesReferencia);
        $criteria->compare('versaoxml', $this->versaoxml);
        $criteria->compare('diaInicPresContas', $this->diaInicPresContas);
        $criteria->compare('diaFinaPresContas', $this->diaFinaPresContas);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        )
        );
    }

}