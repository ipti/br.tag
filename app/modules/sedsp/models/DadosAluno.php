<?php
class DadosAluno {
    private $outSucesso;
    public $outProcessoID;
    private $outAluno;
    public $outErro;
    private $inName;
    private $outListaAlunos;

    public function __construct($name,$json) {

        $data = json_decode($json);
        $this->inName = $name;
        $this->outListaAlunos = $data->outListaAlunos;
        $this->outSucesso = $data->outSucesso;
        $this->outErro = $data->outErro;
        $this->outProcessoID = $data->outProcessoID;
        $this->outAluno = new Aluno($data->outListaAlunos[0]);
    }
    public function getoutSucesso() {
        return $this->outSucesso;
    }
    public function getoutAluno(){
        if(count($this->outListaAlunos) > 1){
            foreach ($this->outListaAlunos as $outListaAluno) {
                $match = similar_text($this->inName, $outListaAluno->outNomeAluno, $pcr);
                $probables[$match]=$outListaAluno;
            }
            ksort($probables);
            return new Aluno($probables[0]);
        }else{
            return new Aluno($this->outListaAlunos[0]);
        }
    }
}
