<?php

/**
 * Componente para controle de feature flag do sistema.
 * Todas as Features precisam obrigatoriamente iniciar sua chave com "FEAT_" + NOME_DA_FEATURE
 *
 */
class FeaturesComponent extends CApplicationComponent
{

    /**
     * Checa se feature está ativa no sistema
     *
     * @var string $featureKey
     *
     * @return bool
     */
    public function isEnable($featureKey)
    {
        $feature = InstanceConfig::model()->findByAttributes(["parameter_key" => $featureKey]);

        if($feature->value){
            return (bool) $feature->value;
        }

        return false;
    }

    /**
     * Desativa uma feature para instancia
     *
     * @var string $featureKey
     *
     * @return void
     */
    public function disable($featureKey)
    {
        $feature = InstanceConfig::model()->findByAttributes(["parameter_key" => $featureKey]);

        if ($feature === false) {
            throw new Exception("Feature não encontrada na base de dados", 1);
        }

        $feature->value = false;
        $result = $feature->save();

        if ($result === false) {
            throw new Exception("Não foi possível desativar feature, verifique se ela existe na base de dados", 1);
        }
    }

    /**
     * Ativa uma feature para instancia
     *
     * @var string $featureKey
     *
     * @return void
     */
    public function enable($featureKey)
    {
        $feature = InstanceConfig::model()->findByAttributes(["parameter_key" => $featureKey]);

        if ($feature === false) {
            throw new Exception("Feature não encontrada na base de dados", 1);
        }

        $feature->value = true;
        $result = $feature->save();

        if ($result === false) {
            throw new Exception("Não foi possível ativar feature, verifique se ela existe na base de dados", 1);
        }
    }

    /**
     * Lista todas a features da instancia
     *
     * @var string $featureKey
     *
     * @return []
     */
    public function listAll()
    {
        $features = InstanceConfig::model()->findAll("parameter_key LIKE '%FEAT_%'");
        return $features;
    }

}
