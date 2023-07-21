<?php

class OutDeficiencia
{
	private $outMobilidadeReduzida;
    private $outTipoMobilidadeReduzida;
    private $outCuidador;
    private $outTipoCuidador;
    private $outProfSaude;
    private $outTipoProfSaude;

	/**
	 * Summary of __construct
	 * @param OutDeficiencia $deficiencia
	 */
	public function __construct($deficiencia) {
		$this->outMobilidadeReduzida = $deficiencia->outMobilidadeReduzida;
		$this->outTipoMobilidadeReduzida = $deficiencia->outTipoMobilidadeReduzida;
		$this->outCuidador = $deficiencia->outCuidador;
		$this->outTipoCuidador = $deficiencia->outTipoCuidador;
		$this->outProfSaude = $deficiencia->outProfSaude;
		$this->outTipoProfSaude = $deficiencia->outTipoProfSaude;
	}
	

	public function getOutMobilidadeReduzida(): string
	{
		return $this->outMobilidadeReduzida;
	}

	public function getOutTipoMobilidadeReduzida(): string
	{
		return $this->outTipoMobilidadeReduzida;
	}

	public function getOutCuidador(): string
	{
		return $this->outCuidador;
	}

	public function getOutTipoCuidador(): string
	{
		return $this->outTipoCuidador;
	}

	public function getOutProfSaude(): string
	{
		return $this->outProfSaude;
	}

	public function getOutTipoProfSaude(): string
	{
		return $this->outTipoProfSaude;
	}
}