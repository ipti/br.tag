<?php
class DadosAluno {
    private $outSucesso;
    public $outProcessoID;
    public $outAluno;
    public $outErro;

    public function __construct($json) {
        $data = json_decode($json);
        $this->outSucesso = $data->outSucesso;
        $this->outErro = $data->outErro;
        $this->outProcessoID = $data->outProcessoID;
        $this->outAluno = new Aluno($data->outListaAlunos[0]);
    }
    public function getoutSucesso() {
        return $this->outSucesso;
    }
}
