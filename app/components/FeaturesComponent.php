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
     * @return bool
     */
    public function disable($featureKey)
    {
        $feature = InstanceConfig::model()->findByAttributes(["parameter_key" => $featureKey]);
        $feature->value = false;
        $result = $feature->save();

        if ($result === false) {
            throw new Exception("Não foi possível atualizar esse registro, veja se ainda está", 1);
        }

        return $feature->value ?? false;
    }

    /**
     * Ativa uma feature para instancia
     *
     * @var string $featureKey
     *
     * @return bool
     */
    public function enable($featureKey)
    {
        $feature = InstanceConfig::model()->findByAttributes(["parameter_key" => $featureKey]);
        $feature->value = true;
        $result = $feature->save();

        if ($result === false) {
            throw new Exception("Não foi possível atualizar esse registro, veja se ainda está", 1);
        }

        return $feature->value ?? false;
    }

    /**
     * Lista todas a features da instancia
     *
     * @var string $featureKey
     *
     * @return bool
     */
    public function listAll()
    {
        $features = InstanceConfig::model()->findAll("parameter_key LIKE '%FEAT_%'");
        return $features;
    }

}

?>
