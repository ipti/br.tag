<?php

class DadosAluno {
    public $outSucesso;
    public $outProcessoID;
    public $outAluno;

    public function __construct($json) {
        $data = json_decode($json);

        $this->outSucesso = $data->outSucesso;
        $this->outProcessoID = $data->outProcessoID;
        $this->outAluno = new Aluno($data->outAluno);
    }
}

class Aluno {
    public $outNomeAluno;
    public $outNomeMae;
    public $outNumRA;
    public $outDigitoRA;
    public $outSiglaUFRA;

    public function __construct($data) {
        $this->outNomeAluno = $data->outNomeAluno;
        $this->outNomeMae = $data->outNomeMae;
        $this->outNumRA = $data->outNumRA;
        $this->outDigitoRA = $data->outDigitoRA;
        $this->outSiglaUFRA = $data->outSiglaUFRA;
    }
}

?>