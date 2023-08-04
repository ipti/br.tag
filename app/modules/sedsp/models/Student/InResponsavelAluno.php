<?php

class InResponsavelAluno implements JsonSerializable
{
    public $inDocumentosAluno;
    public $inAluno;

    public function __construct(InDocumentos $inDocumentosAluno, InAluno $inAluno)
    {
        $this->inDocumentosAluno = $inDocumentosAluno; 
        $this->inAluno = $inAluno;
    }

    /**
     * Get the value of inDocumentosAluno
     */
    public function getInDocumentosAluno()
    {
        return $this->inDocumentosAluno;
    }

    /**
     * Set the value of inDocumentosAluno
     */
    public function setInDocumentosAluno($inDocumentosAluno): self
    {
        $this->inDocumentosAluno = $inDocumentosAluno;

        return $this;
    }

    /**
     * Get the value of inAluno
     */
    public function getInAluno()
    {
        return $this->inAluno;
    }

    /**
     * Set the value of inAluno
     */
    public function setInAluno($inAluno): self
    {
        $this->inAluno = $inAluno;

        return $this;
    }

    function jsonSerialize() {
        return get_object_vars($this);
    }
}
