<?php 
class OutListaTiposEnsino
{
	/**
	 * @var OutTipoEnsino[]|null
	 */
	public $outTipoEnsino;

	/**
	 * @var string|null
	 */
	public $outProcessoID;

	/**
	 * @param OutTipoEnsino[]|null $outTipoEnsino
	 * @param string|null $outProcessoID
	 */
	public function __construct(?array $outTipoEnsino, ?string $outProcessoID)
	{
		$this->outTipoEnsino = $outTipoEnsino;
		$this->outProcessoID = $outProcessoID;
	}

	/**
	 * @return OutTipoEnsino[]|null
	 */
	public function getOutTipoEnsino(): ?array
	{
		return $this->outTipoEnsino;
	}

	/**
	 * @return string|null
	 */
	public function getOutProcessoId(): ?string
	{
		return $this->outProcessoID;
	}

	/**
	 * @param OutTipoEnsino[]|null $outTipoEnsino
	 * @return self
	 */
	public function setOutTipoEnsino(?array $outTipoEnsino): self
	{
		$this->outTipoEnsino = $outTipoEnsino;
		return $this;
	}

	/**
	 * @param string|null $outProcessoID
	 * @return self
	 */
	public function setOutProcessoId(?string $outProcessoID): self
	{
		$this->outProcessoID = $outProcessoID;
		return $this;
	}

	/**
	 * @param array $data
	 * @return self
	 */
	public static function fromJson(array $data): self
	{
		return new self(
			($data['outTipoEnsino'] ?? null) !== null ? array_map(static function($data) {
				return OutTipoEnsino::fromJson($data);
			}, $data['outTipoEnsino']) : null,
			$data['outProcessoID'] ?? null
		);
	}
}


?>