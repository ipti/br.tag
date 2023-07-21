<?php 

/**
 * Summary of inNumClasse
 */
class InClassroom implements JsonSerializable
{
    public $inNumClasse;

	/**
	 * Summary of __construct
	 * @param string $inNumClasse
	 */
	public function __construct($inNumClasse)
	{
		$this->inNumClasse = $inNumClasse;
	}
	
	/**
	 * @return string
	 */
	public function getInNumClasse() {
		return $this->inNumClasse;
	}

	/**
	 * @param string $inNumClasse 
	 * @return self
	 */
	public function setInNumClasse($inNumClasse): self {
		$this->inNumClasse = $inNumClasse;
		return $this;
	}

    function jsonSerialize() {
        return get_object_vars($this);
    }
}
