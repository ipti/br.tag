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

    /**
     * Get the value of outNome
     */
    public function getOutNome()
    {
        return $this->outNome;
    }

    /**
     * Set the value of outNome
     */
    public function setOutNome($outNome): self
    {
        $this->outNome = $outNome;

        return $this;
    }

    /**
     * Get the value of outResponsabilidade
     */
    public function getOutResponsabilidade()
    {
        return $this->outResponsabilidade;
    }

    /**
     * Set the value of outResponsabilidade
     */
    public function setOutResponsabilidade($outResponsabilidade): self
    {
        $this->outResponsabilidade = $outResponsabilidade;

        return $this;
    }

    /**
     * Get the value of outCodTipoResponsabilidade
     */
    public function getOutCodTipoResponsabilidade()
    {
        return $this->outCodTipoResponsabilidade;
    }

    /**
     * Set the value of outCodTipoResponsabilidade
     */
    public function setOutCodTipoResponsabilidade($outCodTipoResponsabilidade): self
    {
        $this->outCodTipoResponsabilidade = $outCodTipoResponsabilidade;

        return $this;
    }

    /**
     * Get the value of outLogin
     */
    public function getOutLogin()
    {
        return $this->outLogin;
    }

    /**
     * Set the value of outLogin
     */
    public function setOutLogin($outLogin): self
    {
        $this->outLogin = $outLogin;

        return $this;
    }

    /**
     * Get the value of outCPF
     */
    public function getOutCPF()
    {
        return $this->outCPF;
    }

    /**
     * Set the value of outCPF
     */
    public function setOutCPF($outCPF): self
    {
        $this->outCPF = $outCPF;

        return $this;
    }

    /**
     * Get the value of outRGRNM
     */
    public function getOutRGRNM()
    {
        return $this->outRGRNM;
    }

    /**
     * Set the value of outRGRNM
     */
    public function setOutRGRNM($outRGRNM): self
    {
        $this->outRGRNM = $outRGRNM;

        return $this;
    }

    /**
     * Get the value of outTipoOrigem
     */
    public function getOutTipoOrigem()
    {
        return $this->outTipoOrigem;
    }

    /**
     * Set the value of outTipoOrigem
     */
    public function setOutTipoOrigem($outTipoOrigem): self
    {
        $this->outTipoOrigem = $outTipoOrigem;

        return $this;
    }

    /**
     * Get the value of outNRRG
     */
    public function getOutNRRG()
    {
        return $this->outNRRG;
    }

    /**
     * Set the value of outNRRG
     */
    public function setOutNRRG($outNRRG): self
    {
        $this->outNRRG = $outNRRG;

        return $this;
    }

    /**
     * Get the value of outDigitoRG
     */
    public function getOutDigitoRG()
    {
        return $this->outDigitoRG;
    }

    /**
     * Set the value of outDigitoRG
     */
    public function setOutDigitoRG($outDigitoRG): self
    {
        $this->outDigitoRG = $outDigitoRG;

        return $this;
    }

    /**
     * Get the value of outUFRG
     */
    public function getOutUFRG()
    {
        return $this->outUFRG;
    }

    /**
     * Set the value of outUFRG
     */
    public function setOutUFRG($outUFRG): self
    {
        $this->outUFRG = $outUFRG;

        return $this;
    }

    /**
     * Get the value of outCodSexo
     */
    public function getOutCodSexo()
    {
        return $this->outCodSexo;
    }

    /**
     * Set the value of outCodSexo
     */
    public function setOutCodSexo($outCodSexo): self
    {
        $this->outCodSexo = $outCodSexo;

        return $this;
    }

    /**
     * Get the value of outCodEstadoCivil
     */
    public function getOutCodEstadoCivil()
    {
        return $this->outCodEstadoCivil;
    }

    /**
     * Set the value of outCodEstadoCivil
     */
    public function setOutCodEstadoCivil($outCodEstadoCivil): self
    {
        $this->outCodEstadoCivil = $outCodEstadoCivil;

        return $this;
    }

    /**
     * Get the value of outEmailResponsavel
     */
    public function getOutEmailResponsavel()
    {
        return $this->outEmailResponsavel;
    }

    /**
     * Set the value of outEmailResponsavel
     */
    public function setOutEmailResponsavel($outEmailResponsavel): self
    {
        $this->outEmailResponsavel = $outEmailResponsavel;

        return $this;
    }

    /**
     * Get the value of outDataNascimento
     */
    public function getOutDataNascimento()
    {
        return $this->outDataNascimento;
    }

    /**
     * Set the value of outDataNascimento
     */
    public function setOutDataNascimento($outDataNascimento): self
    {
        $this->outDataNascimento = $outDataNascimento;

        return $this;
    }

    /**
     * Get the value of outCidadeNascimento
     */
    public function getOutCidadeNascimento()
    {
        return $this->outCidadeNascimento;
    }

    /**
     * Set the value of outCidadeNascimento
     */
    public function setOutCidadeNascimento($outCidadeNascimento): self
    {
        $this->outCidadeNascimento = $outCidadeNascimento;

        return $this;
    }

    /**
     * Get the value of outEnderecoResidencial
     */
    public function getOutEnderecoResidencial()
    {
        return $this->outEnderecoResidencial;
    }

    /**
     * Set the value of outEnderecoResidencial
     */
    public function setOutEnderecoResidencial($outEnderecoResidencial): self
    {
        $this->outEnderecoResidencial = $outEnderecoResidencial;

        return $this;
    }

    /**
     * Get the value of outLogradouro
     */
    public function getOutLogradouro()
    {
        return $this->outLogradouro;
    }

    /**
     * Set the value of outLogradouro
     */
    public function setOutLogradouro($outLogradouro): self
    {
        $this->outLogradouro = $outLogradouro;

        return $this;
    }

    /**
     * Get the value of outNumero
     */
    public function getOutNumero()
    {
        return $this->outNumero;
    }

    /**
     * Set the value of outNumero
     */
    public function setOutNumero($outNumero): self
    {
        $this->outNumero = $outNumero;

        return $this;
    }

    /**
     * Get the value of outBairro
     */
    public function getOutBairro()
    {
        return $this->outBairro;
    }

    /**
     * Set the value of outBairro
     */
    public function setOutBairro($outBairro): self
    {
        $this->outBairro = $outBairro;

        return $this;
    }

    /**
     * Get the value of outNomeCidade
     */
    public function getOutNomeCidade()
    {
        return $this->outNomeCidade;
    }

    /**
     * Set the value of outNomeCidade
     */
    public function setOutNomeCidade($outNomeCidade): self
    {
        $this->outNomeCidade = $outNomeCidade;

        return $this;
    }

    /**
     * Get the value of outUFCidade
     */
    public function getOutUFCidade()
    {
        return $this->outUFCidade;
    }

    /**
     * Set the value of outUFCidade
     */
    public function setOutUFCidade($outUFCidade): self
    {
        $this->outUFCidade = $outUFCidade;

        return $this;
    }

    /**
     * Get the value of outLatitude
     */
    public function getOutLatitude()
    {
        return $this->outLatitude;
    }

    /**
     * Set the value of outLatitude
     */
    public function setOutLatitude($outLatitude): self
    {
        $this->outLatitude = $outLatitude;

        return $this;
    }

    /**
     * Get the value of outLongitude
     */
    public function getOutLongitude()
    {
        return $this->outLongitude;
    }

    /**
     * Set the value of outLongitude
     */
    public function setOutLongitude($outLongitude): self
    {
        $this->outLongitude = $outLongitude;

        return $this;
    }

    /**
     * Get the value of outCep
     */
    public function getOutCep()
    {
        return $this->outCep;
    }

    /**
     * Set the value of outCep
     */
    public function setOutCep($outCep): self
    {
        $this->outCep = $outCep;

        return $this;
    }

    /**
     * Get the value of outLstTelefone
     */
    public function getOutLstTelefone()
    {
        return $this->outLstTelefone;
    }

    /**
     * Set the value of outLstTelefone
     */
    public function setOutLstTelefone($outLstTelefone): self
    {
        $this->outLstTelefone = $outLstTelefone;

        return $this;
    }

    /**
     * Get the value of outDDDNumero
     */
    public function getOutDDDNumero()
    {
        return $this->outDDDNumero;
    }

    /**
     * Set the value of outDDDNumero
     */
    public function setOutDDDNumero($outDDDNumero): self
    {
        $this->outDDDNumero = $outDDDNumero;

        return $this;
    }

    /**
     * Get the value of outNumeroTelefone
     */
    public function getOutNumeroTelefone()
    {
        return $this->outNumeroTelefone;
    }

    /**
     * Set the value of outNumeroTelefone
     */
    public function setOutNumeroTelefone($outNumeroTelefone): self
    {
        $this->outNumeroTelefone = $outNumeroTelefone;

        return $this;
    }

    /**
     * Get the value of outTipoTelefone
     */
    public function getOutTipoTelefone()
    {
        return $this->outTipoTelefone;
    }

    /**
     * Set the value of outTipoTelefone
     */
    public function setOutTipoTelefone($outTipoTelefone): self
    {
        $this->outTipoTelefone = $outTipoTelefone;

        return $this;
    }

    /**
     * Get the value of outDescTipoTelefone
     */
    public function getOutDescTipoTelefone()
    {
        return $this->outDescTipoTelefone;
    }

    /**
     * Set the value of outDescTipoTelefone
     */
    public function setOutDescTipoTelefone($outDescTipoTelefone): self
    {
        $this->outDescTipoTelefone = $outDescTipoTelefone;

        return $this;
    }

    /**
     * Get the value of outErro
     */
    public function getOutErro()
    {
        return $this->outErro;
    }

    /**
     * Set the value of outErro
     */
    public function setOutErro($outErro): self
    {
        $this->outErro = $outErro;

        return $this;
    }

    /**
     * Get the value of outProcessoID
     */
    public function getOutProcessoID()
    {
        return $this->outProcessoID;
    }

    /**
     * Set the value of outProcessoID
     */
    public function setOutProcessoID($outProcessoID): self
    {
        $this->outProcessoID = $outProcessoID;

        return $this;
    }
}
