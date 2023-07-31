<?php 

class InDeficiencia implements JsonSerializable
{
	public $inCodNecessidade;

	public $inMobilidadeReduzida;

	public $inTipoMobilidadeReduzida;

	public $inCuidador;

	public $inTipoCuidador;

	public $inProfSaude;

	public $inTipoProfSaude;

	/**
	 * @param string $inCodNecessidade
	 * @param int $inMobilidadeReduzida
	 * @param string $inTipoMobilidadeReduzida
	 * @param int $inCuidador
	 * @param string $inTipoCuidador
	 * @param int $inProfSaude
	 * @param string $inTipoProfSaude
	 */
	public function __construct($inDeficiencia) {
		$inDeficiencia = (object) $inDeficiencia;
		$this->inCodNecessidade = $inDeficiencia->inCodNecessidade;
		$this->inMobilidadeReduzida = $inDeficiencia->inMobilidadeReduzida;
		$this->inTipoMobilidadeReduzida = $inDeficiencia->inTipoMobilidadeReduzida;
		$this->inCuidador = $inDeficiencia->inCuidador;
		$this->inTipoCuidador = $inDeficiencia->inTipoCuidador;
		$this->inProfSaude = $inDeficiencia->inProfSaude;
		$this->inTipoProfSaude = $inDeficiencia->inTipoProfSaude;
	}

	/**
	 * @param string $inCodNecessidade
	 * @return self
	 */
	public function setInCodNecessidade(string $inCodNecessidade): self
	{
		$this->inCodNecessidade = $inCodNecessidade;
		return $this;
	}

	/**
	 * @param int $inMobilidadeReduzida
	 * @return self
	 */
	public function setInMobilidadeReduzida(int $inMobilidadeReduzida): self
	{
		$this->inMobilidadeReduzida = $inMobilidadeReduzida;
		return $this;
	}

	/**
	 * @param string $inTipoMobilidadeReduzida
	 * @return self
	 */
	public function setInTipoMobilidadeReduzida(string $inTipoMobilidadeReduzida): self
	{
		$this->inTipoMobilidadeReduzida = $inTipoMobilidadeReduzida;
		return $this;
	}

	/**
	 * @param int $inCuidador
	 * @return self
	 */
	public function setInCuidador(int $inCuidador): self
	{
		$this->inCuidador = $inCuidador;
		return $this;
	}

	/**
	 * @param string $inTipoCuidador
	 * @return self
	 */
	public function setInTipoCuidador(string $inTipoCuidador): self
	{
		$this->inTipoCuidador = $inTipoCuidador;
		return $this;
	}

	/**
	 * @param int $inProfSaude
	 * @return self
	 */
	public function setInProfSaude(int $inProfSaude): self
	{
		$this->inProfSaude = $inProfSaude;
		return $this;
	}

	/**
	 * @param string $inTipoProfSaude
	 * @return self
	 */
	public function setInTipoProfSaude(string $inTipoProfSaude): self
	{
		$this->inTipoProfSaude = $inTipoProfSaude;
		return $this;
	}

	function jsonSerialize()
    {
        $filteredProps = array_filter(get_object_vars($this), function ($value) {
            return $value !== null;
        });

        return $filteredProps;
    }
}

