<?php

class OutResponsaveis
{
    public $outNome;
    public $outResponsabilidade;
    public $outCodTipoResponsabilidade;
    public $outLogin;
    public $outCPF;
    public $outRGRNM;
    public $outTipoOrigem;
    public $outNRRG;
    public $outDigitoRG;
    public $outUFRG;
    public $outCodSexo;
    public $outCodEstadoCivil;
    public $outEmailResponsavel;
    public $outDataNascimento;
    public $outCidadeNascimento;
    public $outEnderecoResidencial;
    public $outLogradouro;
    public $outNumero;
    public $outBairro;
    public $outNomeCidade;
    public $outUFCidade;
    public $outLatitude;
    public $outLongitude;
    public $outCep;
    public $outLstTelefone;
    public $outDDDNumero;
    public $outNumeroTelefone;
    public $outTipoTelefone;
    public $outDescTipoTelefone;
    public $outErro;
    public $outProcessoID;

    public function __construct($inResponsaveis)
    {
        $this->outNome = $inResponsaveis->outNome;
        $this->outResponsabilidade = $inResponsaveis->outResponsabilidade;
        $this->outCodTipoResponsabilidade = $inResponsaveis->outCodTipoResponsabilidade;
        $this->outLogin = $inResponsaveis->outLogin;
        $this->outCPF = $inResponsaveis->outCPF;
        $this->outRGRNM = $inResponsaveis->outRGRNM;
        $this->outTipoOrigem = $inResponsaveis->outTipoOrigem;
        $this->outNRRG = $inResponsaveis->outNRRG;
        $this->outDigitoRG = $inResponsaveis->outDigitoRG;
        $this->outUFRG = $inResponsaveis->outUFRG;
        $this->outCodSexo = $inResponsaveis->outCodSexo;
        $this->outCodEstadoCivil = $inResponsaveis->outCodEstadoCivil;
        $this->outEmailResponsavel = $inResponsaveis->outEmailResponsavel;
        $this->outDataNascimento = $inResponsaveis->outDataNascimento;
        $this->outCidadeNascimento = $inResponsaveis->outCidadeNascimento;
        $this->outEnderecoResidencial = $inResponsaveis->outEnderecoResidencial;
        $this->outLogradouro = $inResponsaveis->outLogradouro;
        $this->outNumero = $inResponsaveis->outNumero;
        $this->outBairro = $inResponsaveis->outBairro;
        $this->outNomeCidade = $inResponsaveis->outNomeCidade;
        $this->outUFCidade = $inResponsaveis->outUFCidade;
        $this->outLatitude = $inResponsaveis->outLatitude;
        $this->outLongitude = $inResponsaveis->outLongitude;
        $this->outCep = $inResponsaveis->outCep;
        $this->outLstTelefone = $inResponsaveis->outLstTelefone;
        $this->outDDDNumero = $inResponsaveis->outDDDNumero;
        $this->outNumeroTelefone = $inResponsaveis->outNumeroTelefone;
        $this->outTipoTelefone = $inResponsaveis->outTipoTelefone;
        $this->outDescTipoTelefone = $inResponsaveis->outDescTipoTelefone;
        $this->outErro = $inResponsaveis->outErro;
        $this->outProcessoID = $inResponsaveis->outProcessoID;
    }
}
