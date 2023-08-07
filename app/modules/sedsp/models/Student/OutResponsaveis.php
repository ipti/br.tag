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
	public $outUFNascimento;
	public $outNomePaisNascimento;
	public $outEnderecoResidencial;
	/** @var OutLstTelefone[]|null */
	public $outLstTelefone;

	/**
	 * @param OutLstTelefone[]|null $outLstTelefone
	 */
	public function __construct(
		?string $outNome,
		?string $outResponsabilidade,
		?string $outCodTipoResponsabilidade,
		?string $outLogin,
		?string $outCPF,
		?string $outRGRNM,
		?string $outTipoOrigem,
		?string $outNRRG,
		?string $outDigitoRG,
		?string $outUFRG,
		?string $outCodSexo,
		?string $outCodEstadoCivil,
		?string $outEmailResponsavel,
		?string $outDataNascimento,
		?string $outCidadeNascimento,
		?string $outUFNascimento,
		?string $outNomePaisNascimento,
		?OutEnderecoResidencial $outEnderecoResidencial,
		?array $outLstTelefone
	) {
		$this->outNome = $outNome;
		$this->outResponsabilidade = $outResponsabilidade;
		$this->outCodTipoResponsabilidade = $outCodTipoResponsabilidade;
		$this->outLogin = $outLogin;
		$this->outCPF = $outCPF;
		$this->outRGRNM = $outRGRNM;
		$this->outTipoOrigem = $outTipoOrigem;
		$this->outNRRG = $outNRRG;
		$this->outDigitoRG = $outDigitoRG;
		$this->outUFRG = $outUFRG;
		$this->outCodSexo = $outCodSexo;
		$this->outCodEstadoCivil = $outCodEstadoCivil;
		$this->outEmailResponsavel = $outEmailResponsavel;
		$this->outDataNascimento = $outDataNascimento;
		$this->outCidadeNascimento = $outCidadeNascimento;
		$this->outUFNascimento = $outUFNascimento;
		$this->outNomePaisNascimento = $outNomePaisNascimento;
		$this->outEnderecoResidencial = $outEnderecoResidencial;
		$this->outLstTelefone = $outLstTelefone;
	}

	public function getOutNome(): ?string
	{
		return $this->outNome;
	}

	public function getOutResponsabilidade(): ?string
	{
		return $this->outResponsabilidade;
	}

	public function getOutCodTipoResponsabilidade(): ?string
	{
		return $this->outCodTipoResponsabilidade;
	}

	public function getOutLogin(): ?string
	{
		return $this->outLogin;
	}

	public function getOutCpf(): ?string
	{
		return $this->outCPF;
	}

	public function getOutRgrnm(): ?string
	{
		return $this->outRGRNM;
	}

	public function getOutTipoOrigem(): ?string
	{
		return $this->outTipoOrigem;
	}

	public function getOutNrrg(): ?string
	{
		return $this->outNRRG;
	}

	public function getOutDigitoRg(): ?string
	{
		return $this->outDigitoRG;
	}

	public function getOutUfrg(): ?string
	{
		return $this->outUFRG;
	}

	public function getOutCodSexo(): ?string
	{
		return $this->outCodSexo;
	}

	public function getOutCodEstadoCivil(): ?string
	{
		return $this->outCodEstadoCivil;
	}

	public function getOutEmailResponsavel(): ?string
	{
		return $this->outEmailResponsavel;
	}

	public function getOutDataNascimento(): ?string
	{
		return $this->outDataNascimento;
	}

	public function getOutCidadeNascimento(): ?string
	{
		return $this->outCidadeNascimento;
	}

	public function getOutUfNascimento(): ?string
	{
		return $this->outUFNascimento;
	}

	public function getOutNomePaisNascimento(): ?string
	{
		return $this->outNomePaisNascimento;
	}

	public function getOutEnderecoResidencial(): ?OutEnderecoResidencial
	{
		return $this->outEnderecoResidencial;
	}

	/**
	 * @return OutLstTelefone[]|null
	 */
	public function getOutLstTelefone(): ?array
	{
		return $this->outLstTelefone;
	}

	public function setOutNome(?string $outNome): self
	{
		$this->outNome = $outNome;
		return $this;
	}

	public function setOutResponsabilidade(?string $outResponsabilidade): self
	{
		$this->outResponsabilidade = $outResponsabilidade;
		return $this;
	}

	public function setOutCodTipoResponsabilidade(?string $outCodTipoResponsabilidade): self
	{
		$this->outCodTipoResponsabilidade = $outCodTipoResponsabilidade;
		return $this;
	}

	public function setOutLogin(?string $outLogin): self
	{
		$this->outLogin = $outLogin;
		return $this;
	}

	public function setOutCpf(?string $outCPF): self
	{
		$this->outCPF = $outCPF;
		return $this;
	}

	public function setOutRgrnm(?string $outRGRNM): self
	{
		$this->outRGRNM = $outRGRNM;
		return $this;
	}

	public function setOutTipoOrigem(?string $outTipoOrigem): self
	{
		$this->outTipoOrigem = $outTipoOrigem;
		return $this;
	}

	public function setOutNrrg(?string $outNRRG): self
	{
		$this->outNRRG = $outNRRG;
		return $this;
	}

	public function setOutDigitoRg(?string $outDigitoRG): self
	{
		$this->outDigitoRG = $outDigitoRG;
		return $this;
	}

	public function setOutUfrg(?string $outUFRG): self
	{
		$this->outUFRG = $outUFRG;
		return $this;
	}

	public function setOutCodSexo(?string $outCodSexo): self
	{
		$this->outCodSexo = $outCodSexo;
		return $this;
	}

	public function setOutCodEstadoCivil(?string $outCodEstadoCivil): self
	{
		$this->outCodEstadoCivil = $outCodEstadoCivil;
		return $this;
	}

	public function setOutEmailResponsavel(?string $outEmailResponsavel): self
	{
		$this->outEmailResponsavel = $outEmailResponsavel;
		return $this;
	}

	public function setOutDataNascimento(?string $outDataNascimento): self
	{
		$this->outDataNascimento = $outDataNascimento;
		return $this;
	}

	public function setOutCidadeNascimento(?string $outCidadeNascimento): self
	{
		$this->outCidadeNascimento = $outCidadeNascimento;
		return $this;
	}

	public function setOutUfNascimento(?string $outUFNascimento): self
	{
		$this->outUFNascimento = $outUFNascimento;
		return $this;
	}

	public function setOutNomePaisNascimento(?string $outNomePaisNascimento): self
	{
		$this->outNomePaisNascimento = $outNomePaisNascimento;
		return $this;
	}

	public function setOutEnderecoResidencial(?OutEnderecoResidencial $outEnderecoResidencial): self
	{
		$this->outEnderecoResidencial = $outEnderecoResidencial;
		return $this;
	}

	/**
	 * @param OutLstTelefone[]|null $outLstTelefone
	 */
	public function setOutLstTelefone(?array $outLstTelefone): self
	{
		$this->outLstTelefone = $outLstTelefone;
		return $this;
	}

	public static function fromJson(array $data): self
	{
		return new self(
			$data['outNome'] ?? null,
			$data['outResponsabilidade'] ?? null,
			$data['outCodTipoResponsabilidade'] ?? null,
			$data['outLogin'] ?? null,
			$data['outCPF'] ?? null,
			$data['outRGRNM'] ?? null,
			$data['outTipoOrigem'] ?? null,
			$data['outNRRG'] ?? null,
			$data['outDigitoRG'] ?? null,
			$data['outUFRG'] ?? null,
			$data['outCodSexo'] ?? null,
			$data['outCodEstadoCivil'] ?? null,
			$data['outEmailResponsavel'] ?? null,
			$data['outDataNascimento'] ?? null,
			$data['outCidadeNascimento'] ?? null,
			$data['outUFNascimento'] ?? null,
			$data['outNomePaisNascimento'] ?? null,
			($data['outEnderecoResidencial'] ?? null) !== null ? OutEnderecoResidencial::fromJson($data['outEnderecoResidencial']) : null,
			($data['outLstTelefone'] ?? null) !== null ? array_map(static function($data) {
				return OutLstTelefone::fromJson($data);
			}, $data['outLstTelefone']) : null
		);
	}
}
