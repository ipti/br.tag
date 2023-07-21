<?php

class OutDadosPessoais
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
		$this->outNumRA = $dadosPessoais->outNumRA;
		$this->outDigitoRA = $dadosPessoais->outDigitoRA;
		$this->outSiglaUFRA = $dadosPessoais->outSiglaUFRA;
		$this->outNomeAluno = $dadosPessoais->outNomeAluno;
		$this->outDataNascimento = $dadosPessoais->outDataNascimento;
		$this->outCorRaca = $dadosPessoais->outCorRaca;
		$this->outDescCorRaca = $dadosPessoais->outDescCorRaca;
		$this->outCodSexo = $dadosPessoais->outCodSexo;
		$this->outSexo = $dadosPessoais->outSexo;
		$this->outNomeMae = $dadosPessoais->outNomeMae;
		$this->outNomePai = $dadosPessoais->outNomePai;
		$this->outNomeSocial = $dadosPessoais->outNomeSocial;
		$this->outNomeAfetivo = $dadosPessoais->outNomeAfetivo;
		$this->outEmail = $dadosPessoais->outEmail;
		$this->outEmailGoogle = $dadosPessoais->outEmailGoogle;
		$this->outEmailMicrosoft = $dadosPessoais->outEmailMicrosoft;
		$this->outNacionalidade = $dadosPessoais->outNacionalidade;
		$this->outDescNacionalidade = $dadosPessoais->outDescNacionalidade;
		$this->outCodPaisOrigem = $dadosPessoais->outCodPaisOrigem;
		$this->outNomePaisOrigem = $dadosPessoais->outNomePaisOrigem;
		$this->outDataEntradaPais = $dadosPessoais->outDataEntradaPais;
		$this->outBolsaFamilia = $dadosPessoais->outBolsaFamilia;
		$this->outQuilombola = $dadosPessoais->outQuilombola;
		$this->outPossuiInternet = $dadosPessoais->outPossuiInternet;
		$this->outPossuiNotebookSmartphoneTablet = $dadosPessoais->outPossuiNotebookSmartphoneTablet;
		$this->outNomeMunNascto = $dadosPessoais->outNomeMunNascto;
		$this->outUFMunNascto = $dadosPessoais->outUFMunNascto;
		$this->outAlunoFalecido = $dadosPessoais->outAlunoFalecido;
		$this->outDataFalecimento = $dadosPessoais->outDataFalecimento;
		$this->outCodMunNasctoDNE = $dadosPessoais->outCodMunNasctoDNE;
		$this->outCodBolsaFamilia = $dadosPessoais->outCodBolsaFamilia;
		$this->outDoadorOrgaos = $dadosPessoais->outDoadorOrgaos;
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
}