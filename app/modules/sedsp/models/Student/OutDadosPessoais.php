<?php

class OutDadosPessoais implements JsonSerializable
{
	private $outNumRA;
    private $outDigitoRA;
    private $outSiglaUFRA;
    private $outNomeAluno;
    private $outDataNascimento;
    private $outCorRaca;
    private $outDescCorRaca;
    private $outCodSexo;
    private $outSexo;
    private $outNomeMae;
    private $outNomePai;
    private $outNomeSocial;
    private $outNomeAfetivo;
    private $outEmail;
    private $outEmailGoogle;
    private $outEmailMicrosoft;
    private $outNacionalidade;
    private $outDescNacionalidade;
    private $outCodPaisOrigem;
    private $outNomePaisOrigem;
    private $outDataEntradaPais;
    private $outBolsaFamilia;
    private $outQuilombola;
    private $outPossuiInternet;
    private $outPossuiNotebookSmartphoneTablet;
    private $outNomeMunNascto;
    private $outUFMunNascto;
    private $outAlunoFalecido;
    private $outDataFalecimento;
    private $outCodMunNasctoDNE;
    private $outCodBolsaFamilia;
    private $outDoadorOrgaos;


	public function __construct($dadosPessoais) {
		$this->outNumRA = $dadosPessoais->outAluno->outNumRA;
		$this->outDigitoRA = $dadosPessoais->outAluno->outDigitoRA;
		$this->outSiglaUFRA = $dadosPessoais->outAluno->outSiglaUFRA;
		$this->outNomeAluno = $dadosPessoais->outAluno->outNomeAluno;
		$this->outDataNascimento = $dadosPessoais->outAluno->outDataNascimento;
		$this->outCorRaca = $dadosPessoais->outAluno->outCorRaca;
		$this->outDescCorRaca = $dadosPessoais->outAluno->outDescCorRaca;
		$this->outCodSexo = $dadosPessoais->outAluno->outCodSexo;
		$this->outSexo = $dadosPessoais->outAluno->outSexo;
		$this->outNomeMae = $dadosPessoais->outAluno->outNomeMae;
		$this->outNomePai = $dadosPessoais->outAluno->outNomePai;
		$this->outNomeSocial = $dadosPessoais->outAluno->outNomeSocial;
		$this->outNomeAfetivo = $dadosPessoais->outAluno->outNomeAfetivo;
		$this->outEmail = $dadosPessoais->outAluno->outEmail;
		$this->outEmailGoogle = $dadosPessoais->outAluno->outEmailGoogle;
		$this->outEmailMicrosoft = $dadosPessoais->outAluno->outEmailMicrosoft;
		$this->outNacionalidade = $dadosPessoais->outAluno->outNacionalidade;
		$this->outDescNacionalidade = $dadosPessoais->outAluno->outDescNacionalidade;
		$this->outCodPaisOrigem = $dadosPessoais->outAluno->outCodPaisOrigem;
		$this->outNomePaisOrigem = $dadosPessoais->outAluno->outNomePaisOrigem;
		$this->outDataEntradaPais = $dadosPessoais->outAluno->outDataEntradaPais;
		$this->outBolsaFamilia = $dadosPessoais->outAluno->outBolsaFamilia;
		$this->outQuilombola = $dadosPessoais->outAluno->outQuilombola;
		$this->outPossuiInternet = $dadosPessoais->outAluno->outPossuiInternet;
		$this->outPossuiNotebookSmartphoneTablet = $dadosPessoais->outAluno->outPossuiNotebookSmartphoneTablet;
		$this->outNomeMunNascto = $dadosPessoais->outAluno->outNomeMunNascto;
		$this->outUFMunNascto = $dadosPessoais->outAluno->outUFMunNascto;
		$this->outAlunoFalecido = $dadosPessoais->outAluno->outAlunoFalecido;
		$this->outDataFalecimento = $dadosPessoais->outAluno->outDataFalecimento;
		$this->outCodMunNasctoDNE = $dadosPessoais->outAluno->outCodMunNasctoDNE;
		$this->outCodBolsaFamilia = $dadosPessoais->outAluno->outCodBolsaFamilia;
		$this->outDoadorOrgaos = $dadosPessoais->outAluno->outDoadorOrgaos;
	}
	
	
	public function getOutNumRa(): string
	{
		return $this->outNumRA;
	}

	public function getOutDigitoRa(): string
	{
		return $this->outDigitoRA;
	}

	public function getOutSiglaUfra(): string
	{
		return $this->outSiglaUFRA;
	}

	public function getOutNomeAluno(): string
	{
		return $this->outNomeAluno;
	}

	public function getOutDataNascimento(): string
	{
		return $this->outDataNascimento;
	}

	public function getOutCorRaca(): string
	{
		return $this->outCorRaca;
	}

	public function getOutDescCorRaca(): string
	{
		return $this->outDescCorRaca;
	}

	public function getOutCodSexo(): string
	{
		return $this->outCodSexo;
	}

	public function getOutSexo(): string
	{
		return $this->outSexo;
	}

	public function getOutNomeMae(): string
	{
		return $this->outNomeMae;
	}

	public function getOutNomePai(): string
	{
		return $this->outNomePai;
	}

	public function getOutNomeSocial(): string
	{
		return $this->outNomeSocial;
	}

	public function getOutNomeAfetivo(): string
	{
		return $this->outNomeAfetivo;
	}

	public function getOutEmail(): string
	{
		return $this->outEmail;
	}

	public function getOutEmailGoogle(): string
	{
		return $this->outEmailGoogle;
	}

	public function getOutEmailMicrosoft(): string
	{
		return $this->outEmailMicrosoft;
	}

	public function getOutNacionalidade(): string
	{
		return $this->outNacionalidade;
	}

	public function getOutDescNacionalidade(): string
	{
		return $this->outDescNacionalidade;
	}

	public function getOutCodPaisOrigem(): string
	{
		return $this->outCodPaisOrigem;
	}

	public function getOutNomePaisOrigem(): string
	{
		return $this->outNomePaisOrigem;
	}

	public function getOutDataEntradaPais(): string
	{
		return $this->outDataEntradaPais;
	}

	public function getOutBolsaFamilia(): string
	{
		return $this->outBolsaFamilia;
	}

	public function getOutQuilombola(): string
	{
		return $this->outQuilombola;
	}

	public function getOutPossuiInternet(): string
	{
		return $this->outPossuiInternet;
	}

	public function getOutPossuiNotebookSmartphoneTablet(): string
	{
		return $this->outPossuiNotebookSmartphoneTablet;
	}

	public function getOutNomeMunNascto(): string
	{
		return $this->outNomeMunNascto;
	}

	public function getOutUfMunNascto(): string
	{
		return $this->outUFMunNascto;
	}

	public function getOutAlunoFalecido(): string
	{
		return $this->outAlunoFalecido;
	}

	public function getOutDataFalecimento(): string
	{
		return $this->outDataFalecimento;
	}

	public function getOutCodMunNasctoDne(): string
	{
		return $this->outCodMunNasctoDNE;
	}

	public function getOutCodBolsaFamilia(): string
	{
		return $this->outCodBolsaFamilia;
	}

	public function getOutDoadorOrgaos(): string
	{
		return $this->outDoadorOrgaos;
	}

	public function jsonSerialize()
    {
		$filteredProps = array_filter(get_object_vars($this), function ($value) {
            return $value !== null;
        });

        return $filteredProps;
    }
}