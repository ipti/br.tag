<?php

class InRastreio implements JsonSerializable
{
    public $inUsuarioRemoto;
    public $inNomeUsuario;
    public $inNumCPF;
    public $inLocalPerfilAcesso;

    public function __construct($inRastreio) {
        $inRastreio = (object) $inRastreio;
        $this->inUsuarioRemoto = $inRastreio->inUsuarioRemoto;
        $this->inNomeUsuario = $inRastreio->inNomeUsuario;
        $this->inNumCPF = $inRastreio->inNumCPF;
        $this->inLocalPerfilAcesso = $inRastreio->inLocalPerfilAcesso;
    }

    public function getInUsuarioRemoto(): string
    {
        return $this->inUsuarioRemoto;
    }

    public function getInNomeUsuario(): string
    {
        return $this->inNomeUsuario;
    }

    public function getInNumCpf(): string
    {
        return $this->inNumCPF;
    }

    public function getInLocalPerfilAcesso(): string
    {
        return $this->inLocalPerfilAcesso;
    }

    function jsonSerialize()
    {
        $filteredProps = array_filter(get_object_vars($this), function ($value) {
            return $value !== null;
        });

        return $filteredProps;
    }
}