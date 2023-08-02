<?php

class InConsultaTurmaClasse
{
    public $inAnoLetivo;
	public $inNumClasse;

	public function __construct(string $inAnoLetivo, string $inNumClasse)
	{
		$this->inAnoLetivo = $inAnoLetivo;
		$this->inNumClasse = $inNumClasse;
	}

    /**
     * Get the value of inAnoLetivo
     */
    public function getInAnoLetivo()
    {
        return $this->inAnoLetivo;
    }

    /**
     * Set the value of inAnoLetivo
     */
    public function setInAnoLetivo($inAnoLetivo): self
    {
        $this->inAnoLetivo = $inAnoLetivo;

        return $this;
    }

	/**
	 * Get the value of inNumClasse
	 */
	public function getInNumClasse()
	{
		return $this->inNumClasse;
	}

	/**
	 * Set the value of inNumClasse
	 */
	public function setInNumClasse($inNumClasse): self
	{
		$this->inNumClasse = $inNumClasse;

		return $this;
	}
}
