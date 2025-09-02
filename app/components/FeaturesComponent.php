<?php 

class FeaturesComponent extends CApplicationComponent
{
    const STATE_KEY = '_features_cache';

    /**
     * Inicializa o componente
     * Carrega todas as features no cache da sessão
     */
    public function init()
    {
        parent::init();

        // Verifica se já existe cache na sessão
        $cache = Yii::app()->user->getState(self::STATE_KEY);
        if ($cache === null) {
            $this->loadAllFeaturesToCache();
        }
    }

    /**
     * Checa se feature está ativa no sistema
     *
     * @param string $featureKey
     * @return bool
     */
    public function isEnable($featureKey)
    {
        $cache = Yii::app()->user->getState(self::STATE_KEY, []);
        return isset($cache[$featureKey]) ? (bool)$cache[$featureKey] : false;
    }

    /**
     * Ativa uma feature
     *
     * @param string $featureKey
     * @throws Exception
     */
    public function enable($featureKey)
    {
        $feature = $this->getFeatureOrFail($featureKey);
        $feature->value = true;

        if (!$feature->save()) {
            throw new Exception("Não foi possível ativar feature '$featureKey'");
        }

        $this->updateSessionCache($featureKey, true);
    }

    /**
     * Desativa uma feature
     *
     * @param string $featureKey
     * @throws Exception
     */
    public function disable($featureKey)
    {
        $feature = $this->getFeatureOrFail($featureKey);
        $feature->value = false;

        if (!$feature->save()) {
            throw new Exception("Não foi possível desativar feature '$featureKey'");
        }

        $this->updateSessionCache($featureKey, false);
    }

    /**
     * Atualiza cache na sessão via setState
     */
    private function updateSessionCache($featureKey, $value)
    {
        $cache = Yii::app()->user->getState(self::STATE_KEY, []);
        $cache[$featureKey] = $value;
        Yii::app()->user->setState(self::STATE_KEY, $cache);
    }

    /**
     * Lista todas as features da instância
     *
     * @return array
     */
    public function listAll()
    {
        $cache = Yii::app()->user->getState(self::STATE_KEY, []);
        return $cache;
    }

    /**
     * Carrega todas as features do banco para o cache da sessão
     */
    public function loadAllFeaturesToCache()
    {
        $features = InstanceConfig::model()->findAll("parameter_key LIKE 'FEAT_%'");
        $cache = [];

        foreach ($features as $f) {
            $cache[$f->parameter_key] = (bool)$f->value;
        }

        Yii::app()->user->setState(self::STATE_KEY, $cache);
    }

    /**
     * Busca feature no banco ou lança exceção
     *
     * @param string $featureKey
     * @return InstanceConfig
     * @throws Exception
     */
    private function getFeatureOrFail($featureKey)
    {
        $feature = InstanceConfig::model()->findByAttributes(["parameter_key" => $featureKey]);
        if (!$feature) {
            throw new Exception("Feature '$featureKey' não encontrada na base de dados");
        }
        return $feature;
    }
}
