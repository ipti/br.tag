<?php 
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