<?php

class OutCertidaoAntiga
{
	public $outNumCertidao;
	public $outNumLivroReg;
	public $outFolhaRegNum;
	public $outNomeMunComarca;
	public $outUFComarca;
	public $outDistritoNasc;

	public function __construct(
		?string $outNumCertidao,
		?string $outNumLivroReg,
		?string $outFolhaRegNum,
		?string $outNomeMunComarca,
		?string $outUFComarca,
		?string $outDistritoNasc
	) {
		$this->outNumCertidao = $outNumCertidao;
		$this->outNumLivroReg = $outNumLivroReg;
		$this->outFolhaRegNum = $outFolhaRegNum;
		$this->outNomeMunComarca = $outNomeMunComarca;
		$this->outUFComarca = $outUFComarca;
		$this->outDistritoNasc = $outDistritoNasc;
	}

	public function getOutNumCertidao(): ?string
	{
		return $this->outNumCertidao;
	}

	public function getOutNumLivroReg(): ?string
	{
		return $this->outNumLivroReg;
	}

	public function getOutFolhaRegNum(): ?string
	{
		return $this->outFolhaRegNum;
	}

	public function getOutNomeMunComarca(): ?string
	{
		return $this->outNomeMunComarca;
	}

	public function getOutUfComarca(): ?string
	{
		return $this->outUFComarca;
	}

	public function getOutDistritoNasc(): ?string
	{
		return $this->outDistritoNasc;
	}

	public function setOutNumCertidao(?string $outNumCertidao): self
	{
		$this->outNumCertidao = $outNumCertidao;
		return $this;
	}

	public function setOutNumLivroReg(?string $outNumLivroReg): self
	{
		$this->outNumLivroReg = $outNumLivroReg;
		return $this;
	}

	public function setOutFolhaRegNum(?string $outFolhaRegNum): self
	{
		$this->outFolhaRegNum = $outFolhaRegNum;
		return $this;
	}

	public function setOutNomeMunComarca(?string $outNomeMunComarca): self
	{
		$this->outNomeMunComarca = $outNomeMunComarca;
		return $this;
	}

	public function setOutUfComarca(?string $outUFComarca): self
	{
		$this->outUFComarca = $outUFComarca;
		return $this;
	}

	public function setOutDistritoNasc(?string $outDistritoNasc): self
	{
		$this->outDistritoNasc = $outDistritoNasc;
		return $this;
	}

 /**
  * Summary of fromJson
  * @param array $data
  * @return self
  */
	public static function fromJson(array $data): self
    {
        return new self(
            $data['outNumCertidao'] ?? null,
            $data['outNumLivroReg'] ?? null,
            $data['outFolhaRegNum'] ?? null,
            $data['outNomeMunComarca'] ?? null,
            $data['outUFComarca'] ?? null,
            $data['outDistritoNasc'] ?? null
        );
    }
}