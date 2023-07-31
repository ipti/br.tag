<?php 

class InCertidaoAntiga implements JsonSerializable
{
	public $inNumCertidao;

	public $inLivro;

	public $inFolha;

	public $inDistritoCertidao;

	public $inMunicipioComarca;

	public $inUFComarca;

	public $inDataEmissaoCertidao;

	/**
	 * @param string $inNumCertidao
	 * @param string $inLivro
	 * @param string $inFolha
	 * @param string $inDistritoCertidao
	 * @param string $inMunicipioComarca
	 * @param string $inUFComarca
	 * @param string $inDataEmissaoCertidao
	 */
	public function __construct($inCertidaoAntiga) {
		$inCertidaoAntiga = (object) $inCertidaoAntiga;
		$this->inNumCertidao = $inCertidaoAntiga->inNumCertidao;
		$this->inLivro = $inCertidaoAntiga->inLivro;
		$this->inFolha = $inCertidaoAntiga->inFolha;
		$this->inDistritoCertidao = $inCertidaoAntiga->inDistritoCertidao;
		$this->inMunicipioComarca = $inCertidaoAntiga->inMunicipioComarca;
		$this->inUFComarca = $inCertidaoAntiga->inUfComarca;
		$this->inDataEmissaoCertidao = $inCertidaoAntiga->inDataEmissaoCertidao;
	}

	/**
	 * Get the value of inNumCertidao
	 */ 
	public function getInNumCertidao()
	{
		return $this->inNumCertidao;
	}

	/**
	 * Set the value of inNumCertidao
	 *
	 * @return  self
	 */ 
	public function setInNumCertidao($inNumCertidao)
	{
		$this->inNumCertidao = $inNumCertidao;

		return $this;
	}

	/**
	 * Get the value of inLivro
	 */ 
	public function getInLivro()
	{
		return $this->inLivro;
	}

	/**
	 * Set the value of inLivro
	 *
	 * @return  self
	 */ 
	public function setInLivro($inLivro)
	{
		$this->inLivro = $inLivro;

		return $this;
	}

	/**
	 * Get the value of inFolha
	 */ 
	public function getInFolha()
	{
		return $this->inFolha;
	}

	/**
	 * Set the value of inFolha
	 *
	 * @return  self
	 */ 
	public function setInFolha($inFolha)
	{
		$this->inFolha = $inFolha;

		return $this;
	}

	/**
	 * Get the value of inDistritoCertidao
	 */ 
	public function getInDistritoCertidao()
	{
		return $this->inDistritoCertidao;
	}

	/**
	 * Set the value of inDistritoCertidao
	 *
	 * @return  self
	 */ 
	public function setInDistritoCertidao($inDistritoCertidao)
	{
		$this->inDistritoCertidao = $inDistritoCertidao;

		return $this;
	}

	/**
	 * Get the value of inMunicipioComarca
	 */ 
	public function getInMunicipioComarca()
	{
		return $this->inMunicipioComarca;
	}

	/**
	 * Set the value of inMunicipioComarca
	 *
	 * @return  self
	 */ 
	public function setInMunicipioComarca($inMunicipioComarca)
	{
		$this->inMunicipioComarca = $inMunicipioComarca;

		return $this;
	}

	/**
	 * Get the value of inUfComarca
	 */ 
	public function getInUfComarca()
	{
		return $this->inUFComarca;
	}

	/**
	 * Set the value of inUfComarca
	 *
	 * @return  self
	 */ 
	public function setInUfComarca($inUfComarca)
	{
		$this->inUFComarca = $inUfComarca;

		return $this;
	}

	/**
	 * Get the value of inDataEmissaoCertidao
	 */ 
	public function getInDataEmissaoCertidao()
	{
		return $this->inDataEmissaoCertidao;
	}

	/**
	 * Set the value of inDataEmissaoCertidao
	 *
	 * @return  self
	 */ 
	public function setInDataEmissaoCertidao($inDataEmissaoCertidao)
	{
		$this->inDataEmissaoCertidao = $inDataEmissaoCertidao;

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
