<?php

class GetExibirFichaAlunoSEDUseCase
{
    function exec(InAluno $inAluno)
    {
        $studentSEDDataSource = new StudentSEDDataSource();
        $response = $studentSEDDataSource->exibirFichaAluno($inAluno);
        return StudentMapper::parseToTAGAlunoFicha($response);
    }
    
}
