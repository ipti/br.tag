<?php

class IMC
{
    public const DESNUTRICAO_GRAVE = 1;
    public const DESNUTRICAO_MODERADA  = 2;
    public const DESNUTRICAO = 3;
    public const NORMAL = 4;
    public const SOBREPESO = 5;
    public const OBESIDADE = 6;



    public function IMCSituation()
    {
        if($this->IMC() < 19) {
            return IMC::DESNUTRICAO;
        } elseif($this->IMC() >= 19 && $this->IMC() < 24.9) {
            return IMC::NORMAL;
        } elseif($this->IMC() >= 25 && $this->IMC() < 29.9) {
            return IMC::SOBREPESO;
        } elseif($this->IMC() >= 30) {
            return IMC::OBESIDADE;
        } else {
            return null;
        }
    }

    public function classificarIMCInfantil($imc, $idade, $genero)
    {
        // Tabelas de classificação do IMC para crianças de acordo com a OMS
        $tabelas_IMC = [
            // Meninos (0 a 5 anos)
            'masculino' => [
                5 => [12.1, 12.9, 16.6, 18.3, 18.4],
                6 => [12.1, 12.9, 16.8, 18.5, 18.6],
                7 => [12.3, 13.0, 17.0, 19.0, 19.1],
                8 => [12.4, 13.2, 17.4, 19.7, 19.8],
                9 => [12.6, 13.4, 17.9, 20.5, 20.6],
                10 => [12.8, 13.6, 18.5, 21.4, 21.5],
                11 => [13.1, 14.0, 19.2, 22.5, 22.6],
                12 => [13.4, 14.4, 19.9, 23.6, 23.7],
                13 => [13.8, 14.8, 20.8, 24.8, 24.9],
                14 => [14.3, 15.4, 21.8, 25.9, 26.0],
                15 => [14.7, 15.9, 22.7, 27.0, 27.1],
                16 => [15.1, 16.4, 23.5, 27.9, 28.0],
                17 => [15.4, 16.8, 24.3, 28.6, 28.7],
                18 => [15.7, 17.2, 24.9, 29.2, 29.3],
            ],

            // Meninas (0 a 5 anos)
            'feminino' => [
                5 => [11.8, 12.6, 16.9, 18.9, 19.0],
                6 => [11.7, 12.6, 17.0, 19.2, 19.3],
                7 => [11.8, 12.6, 17.3, 19.8, 19.9],
                8 => [11.9, 12.8, 17.7, 20.6, 20.7],
                9 => [12.1, 13.0, 18.3, 21.5, 21.6],
                10 => [12.4, 13.4, 19.0, 22.6, 22.7],
                11 => [12.7, 13.8, 19.9, 23.7, 23.8],
                12 => [13.2, 14.3, 20.8, 25.0, 25.1],
                13 => [13.6, 14.8, 21.8, 26.2, 26.3],
                14 => [14.0, 15.3, 22.7, 27.3, 27.4],
                15 => [14.4, 15.8, 23.5, 28.2, 28.3],
                16 => [14.6, 16.1, 24.1, 28.9, 29.0],
                17 => [14.7, 16.3, 24.5, 29.3, 29.4],
                18 => [14.7, 16.3, 24.8, 29.5, 29.6],
            ],
        ];

        // Encontra as faixas de classificação com base na idade e no gênero
        $faixas = $tabelas_IMC[$genero][$idade];

        // Classifica o IMC com base nas faixas definidas
        if ($imc <= $faixas[0]) {
            return IMC::DESNUTRICAO_GRAVE;
        } elseif ($imc <= $faixas[1]) {
            return IMC::DESNUTRICAO_MODERADA;
        } elseif ($imc <= $faixas[2]) {
            return IMC::NORMAL;
        } elseif ($imc <= $faixas[3]) {
            return IMC::SOBREPESO;
        } else {
            return IMC::OBESIDADE;
        }
    }
}
