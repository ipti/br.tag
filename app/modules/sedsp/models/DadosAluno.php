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
