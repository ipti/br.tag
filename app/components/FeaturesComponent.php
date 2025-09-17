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

        $cache = Yii::app()->user->getState(self::STATE_KEY);
        if ($cache === null) {
            $this->loadAllFeaturesToCache();
        }
    }

    /**
     * Checa se feature está ativa no sistema
     *
     * @param TTask|string $featureKey
     * @return bool
     */
    public function isEnable($featureKey): bool
    {
        $featureKey = gettype($featureKey) === 'string' ? $featureKey : $featureKey->value;
        $key = $this->normalizeKey($featureKey);
        $cache = Yii::app()->user->getState(self::STATE_KEY, []);
        return isset($cache[$key]) ? (bool) $cache[$key] : false;
    }

    /**
     * Ativa uma feature
     *
     * @param TTask|string $featureKey
     * @throws Exception
     */
    public function enable($featureKey): void
    {
        $key = $this->normalizeKey($featureKey);
        $feature = $this->getOrCreateFeature($key);
        $feature->active = 1;

        if (!$feature->save()) {
            throw new Exception("Não foi possível ativar a feature '$key'");
        }

        $this->updateSessionCache($key, true);
    }

    /**
     * Desativa uma feature
     *
     * @param TTask|string $featureKey
     * @throws Exception
     */
    public function disable($featureKey): void
    {
        $key = $this->normalizeKey($featureKey);
        $feature = $this->getOrCreateFeature($key);
        $feature->active = 0;

        if (!$feature->save()) {
            throw new Exception("Não foi possível desativar a feature '$key'");
        }

        $this->updateSessionCache($key, false);
    }

    /**
     * Lista todas as features da instância (do cache)
     *
     * @return array
     */
    public function listAll(): array
    {
        return Yii::app()->user->getState(self::STATE_KEY, []);
    }

    /**
     * Recarrega todas as features do banco para o cache da sessão
     */
    public function refreshCache(): void
    {
        $this->loadAllFeaturesToCache();
    }

    /**
     * ------------------- MÉTODOS PRIVADOS -------------------
     */

    /**
     * Normaliza chave (aceita enum ou string)
     */
    private function normalizeKey($featureKey): string
    {
        return ($featureKey instanceof TTask) ? $featureKey->value : (string) $featureKey;
    }

    /**
     * Atualiza cache na sessão
     */
    private function updateSessionCache(string $featureKey, bool $value): void
    {
        $cache = Yii::app()->user->getState(self::STATE_KEY, []);
        $cache[$featureKey] = $value;
        Yii::app()->user->setState(self::STATE_KEY, $cache);
    }

    /**
     * Carrega todas as features do banco para o cache da sessão
     */
    private function loadAllFeaturesToCache(): void
    {
        $features = FeatureFlags::model()->findAll();
        $cache = [];

        foreach ($features as $f) {
            $cache[$f->feature_name] = (bool) $f->active;
        }

        Yii::app()->user->setState(self::STATE_KEY, $cache);
    }

    /**
     * Busca ou cria feature no banco
     *
     * @param string $featureKey
     * @return FeatureFlags
     */
    private function getOrCreateFeature(string $featureKey): FeatureFlags
    {
        $feature = FeatureFlags::model()->findByPk($featureKey);

        if (!$feature) {
            $feature = new FeatureFlags();
            $feature->feature_name = $featureKey;
            $feature->active = 0; // default inativo
            $feature->save();
        }

        return $feature;
    }
}
