<?php

class InResponsavelAluno 
{
    public $inDocumentosAluno;
    public $inAluno;

    public function __construct($inResponsavelAluno)
    {
        $this->inDocumentosAluno = new InDocumentos((object) $inResponsavelAluno['inDocumentosAluno']); 
        $this->inAluno = new InAluno($inResponsavelAluno['inAluno']['inNumRA'], $inResponsavelAluno['inAluno']['inDigitoRA'], $inResponsavelAluno['inAluno']['inSiglaUFRA']);
    }
}
