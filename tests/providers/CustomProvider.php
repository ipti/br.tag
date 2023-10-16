<?php

use Faker\Provider\Base;

class CustomProvider extends Base
{
    public function __construct(\Faker\Generator $generator)
    {
        $this->generator = $generator;
    }

    /**
     * Método para gerar números do CNS.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function cnsNumber()
    {
        $cns = '7';
        $cns .= $this->generator->randomElement(['01', '02', '03']);
        $cns .= $this->generator->numerify('##########');

        return $cns;
    }

    /**
     * Método para gerar Número de Matrícula (Registro Civil - Certidão nova).
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function matriculaRegistroCivil()
    {
        // Primeiro Bloco (6 dígitos)
        $primeiroBloco = $this->generator->numerify('######');

        // Segundo Bloco (2 dígitos)
        $segundoBloco = $this->generator->numberBetween(1, 31);
        $segundoBloco = str_pad($segundoBloco, 2, '0', STR_PAD_LEFT);

        // Terceiro Bloco (2 dígitos)
        $terceiroBloco = $this->generator->numberBetween(1, 12);
        $terceiroBloco = str_pad($terceiroBloco, 2, '0', STR_PAD_LEFT);

        // Quarto Bloco (4 dígitos) 
        $anoAtual = date('Y');
        $quartoBloco = $this->generator->numberBetween(2010, $anoAtual);

        // Quinto Bloco (1 dígito)
        $quintoBloco = '1'; 
        
        // Sexto Bloco (5 dígitos)
        $sextoBloco = $this->generator->numerify('#####');

        // Sétimo Bloco (3 dígitos)
        $setimoBloco = $this->generator->numerify('###');

        // Oitavo Bloco (7 dígitos)
        $oitavoBloco = $this->generator->numerify('#######');

        // Nono Bloco (2 dígitos)
        $nonoBloco = $this->generator->numerify('##');

        // Combina todos os blocos
        $matricula = "$primeiroBloco $segundoBloco $terceiroBloco $quartoBloco $quintoBloco $sextoBloco $setimoBloco $oitavoBloco $nonoBloco";

        return $matricula;
    }

    /**
     * Método que gera o livro de uma certidão.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function bookCivil()
    {
        $bookCivil = $this->generator->numerify('########');

        return $bookCivil;
    }

    /**
     * Método que gera a folha de uma certidão.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function sheedCivil()
    {
        $sheedCivil = $this->generator->numerify('####');

        return $sheedCivil;
    }

    /**
     * Método que gera o termo de uma certidão.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function termCivil()
    {
        $termCivil = $this->generator->numerify('########');
        
        return $termCivil;
    }

    /**
     * Método para gerar NIS (Número de Identificação Social).
     */
    public function nisNumber()
    {
        $nis = '1';
        $nis .= $this->generator->numerify('##########');

        return $nis;
    }

    /**
     * Método para gerar ID INEP.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function inepId()
    {
        $inep = '1';
        $inep .= $this->generator->numerify('#########');

        return $inep;
    }

    /**
     * Método que gera um complemento de uma localização.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function complementLocation()
    {
        $tipos = ['Bloco A', 'Casa 2', 'Sala 301', 'Fundos', 'Ap. 102', 'Lote 15'];

        return $this->generator->randomElement($tipos);
    }
 
}