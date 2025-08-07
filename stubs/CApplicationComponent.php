<?php

/**
 * Yii 1.1 stub - CApplicationComponent
 * Classe base para componentes da aplicação.
 */
class CApplicationComponent extends CComponent
{
    /**
     * @var string ID do componente
     */
    public $id;

    /**
     * @var CApplication a aplicação que este componente pertence
     */
    public $owner;

    /**
     * Inicializa o componente.
     * Chamado após o componente ser configurado.
     */
    public function init()
    {
        // normalmente sobrescrito no projeto real
    }

    /**
     * Retorna o aplicativo ao qual este componente pertence.
     * @return CApplication
     */
    public function getApplication()
    {
        return $this->owner;
    }

    /**
     * Configura o componente.
     * @param array $config configuração inicial
     */
    public function configure($config)
    {
        foreach ($config as $key => $value) {
            $this->$key = $value;
        }
    }
}
