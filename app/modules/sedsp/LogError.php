<?php

class LogError
{
    public function salvarDadosEmArquivo($dados) {
        $caminhoPasta = 'app/modules/sedsp/errors/';

        if (!is_dir($caminhoPasta)) {
            mkdir($caminhoPasta, 0777, true);
        }
    
        $caminhoArquivo = $caminhoPasta . 'errosImports.txt';
        $arquivo = fopen($caminhoArquivo, 'a');

        if ($arquivo) {
            fwrite($arquivo, $dados . PHP_EOL);
            fclose($arquivo);
        } else {
            echo "Erro ao abrir o arquivo.";
        }
    }
}
