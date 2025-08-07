<?php

/**
 * Yii 1.1 stub - CUserIdentity
 * Classe base para autenticação de usuários.
 */
class CUserIdentity extends CComponent implements IUserIdentity
{
    /** @var string nome do usuário */
    public $username;

    /** @var string senha */
    public $password;

    /** @var int código do erro de autenticação */
    protected $errorCode = 100;

    /** @var array estado do usuário autenticado */
    protected $_state = [];

    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * @return bool se a autenticação foi bem-sucedida
     */
    public function authenticate()
    {
        return false;
    }

    /**
     * @return int código do erro de autenticação
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * @return string nome do usuário autenticado
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Armazena um valor associado a uma chave no estado da identidade.
     * @param string $key
     * @param mixed $value
     */
    public function setState($key, $value)
    {
        $this->_state[$key] = $value;
    }

    /**
     * Retorna o valor de uma chave armazenada no estado da identidade.
     * @param string $key
     * @param mixed $defaultValue
     * @return mixed
     */
    public function getState($key, $defaultValue = null)
    {
        return $this->_state[$key] ?? $defaultValue;
    }
}
