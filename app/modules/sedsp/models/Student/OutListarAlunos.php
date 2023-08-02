<?php

/**
 * Summary of OutListarAlunos
 */
class OutListarAlunos 
{
    public $outNumRA;
	public $outDigitoRA;
	public $outSiglaUFRA;
	public $outNomeAluno;
	public $outNomeMae;
	public $outDataNascimento;
	public $outNomePai;

	public function __construct(
		string $outNumRA,
		string $outDigitoRA,
		string $outSiglaUFRA,
		string $outNomeAluno,
		string $outNomeMae,
		string $outDataNascimento,
		?string $outNomePai
	) {
		$this->outNumRA = $outNumRA;
		$this->outDigitoRA = $outDigitoRA;
		$this->outSiglaUFRA = $outSiglaUFRA;
		$this->outNomeAluno = $outNomeAluno;
		$this->outNomeMae = $outNomeMae;
		$this->outDataNascimento = $outDataNascimento;
		$this->outNomePai = $outNomePai;
	}

    /**
     * Get the value of outNumRA
     */
    public function getOutNumRA()
    {
        return $this->outNumRA;
    }

    /**
     * Set the value of outNumRA
     */
    public function setOutNumRA($outNumRA): self
    {
        $this->outNumRA = $outNumRA;

        return $this;
    }

	/**
	 * Get the value of outDigitoRA
	 */
	public function getOutDigitoRA()
	{
		return $this->outDigitoRA;
	}

	/**
	 * Set the value of outDigitoRA
	 */
	public function setOutDigitoRA($outDigitoRA): self
	{
		$this->outDigitoRA = $outDigitoRA;

		return $this;
	}

	/**
	 * Get the value of outSiglaUFRA
	 */
	public function getOutSiglaUFRA()
	{
		return $this->outSiglaUFRA;
	}

	/**
	 * Set the value of outSiglaUFRA
	 */
	public function setOutSiglaUFRA($outSiglaUFRA): self
	{
		$this->outSiglaUFRA = $outSiglaUFRA;

		return $this;
	}

	/**
	 * Get the value of outNomeAluno
	 */
	public function getOutNomeAluno()
	{
		return $this->outNomeAluno;
	}

	/**
	 * Set the value of outNomeAluno
	 */
	public function setOutNomeAluno($outNomeAluno): self
	{
		$this->outNomeAluno = $outNomeAluno;

		return $this;
	}

	/**
	 * Get the value of outNomeMae
	 */
	public function getOutNomeMae()
	{
		return $this->outNomeMae;
	}

	/**
	 * Set the value of outNomeMae
	 */
	public function setOutNomeMae($outNomeMae): self
	{
		$this->outNomeMae = $outNomeMae;

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
	 * Get the value of outNomePai
	 */
	public function getOutNomePai()
	{
		return $this->outNomePai;
	}

	/**
	 * Set the value of outNomePai
	 */
	public function setOutNomePai($outNomePai): self
	{
		$this->outNomePai = $outNomePai;

		return $this;
	}
}
