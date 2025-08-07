<?php

/**
 * Yii 1.1 stub - CClientScript
 * Gerencia a inclusão de scripts e folhas de estilo no HTML.
 */
class CClientScript extends CApplicationComponent
{
    const POS_HEAD = 0;
    const POS_BEGIN = 1;
    const POS_END = 2;
    const POS_LOAD = 3;
    const POS_READY = 4;

    /** @var array scripts registrados */
    public $scripts = [];

    /** @var array pacotes de script */
    public $packages = [];

    /**
     * Registra um arquivo JavaScript.
     * @param string $url
     * @param int $position
     * @return $this
     */
    public function registerScriptFile($url, $position = self::POS_HEAD)
    {
        return $this;
    }

    /**
     * Registra um bloco de script JavaScript.
     * @param string $id
     * @param string $script
     * @param int $position
     * @return $this
     */
    public function registerScript($id, $script, $position = self::POS_READY)
    {
        return $this;
    }

    /**
     * Registra um arquivo CSS.
     * @param string $url
     * @param string $media
     * @return $this
     */
    public function registerCssFile($url, $media = '')
    {
        return $this;
    }

    /**
     * Registra um bloco de CSS.
     * @param string $id
     * @param string $css
     * @return $this
     */
    public function registerCss($id, $css)
    {
        return $this;
    }

    /**
     * Marca um pacote de script como registrado.
     * @param string $name
     * @return $this
     */
    public function registerCoreScript($name)
    {
        return $this;
    }
}
