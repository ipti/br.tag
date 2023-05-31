<?php

namespace SagresEdu;
use JMS\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validation;
use JMS\Serializer\Annotation\XmlElement;

/**
 * Class representing DiretorTType
 *
 * 
 * XSD Type: diretor_t
 */
class DiretorTType
{

    /**
     * @var string $cpfDiretor
     * @SerializedName("edu:cpfDiretor")
     * @XmlElement(cdata=false)
     */
    private $cpfDiretor = null;

    /**
     * @var string $nrAto
     * @SerializedName("edu:nrAto")
     * @XmlElement(cdata=false)
     */
    private $nrAto = null;

    /**
     * Gets as cpfDiretor
     *
     * @return string
     */
    public function getCpfDiretor()
    {
        return $this->cpfDiretor;
    }

    /**
     * Sets a new cpfDiretor
     *
     * @param string $cpfDiretor
     * @return self
     */
    public function setCpfDiretor($cpfDiretor)
    {
        $this->cpfDiretor = $cpfDiretor;
        return $this;
    }

    /**
     * Gets as nrAto
     *
     * @return string
     */
    public function getNrAto()
    {
        return $this->nrAto;
    }

    /**
     * Sets a new nrAto
     *
     * @param string $nrAto
     * @return self
     */
    public function setNrAto($nrAto)
    {
        $this->nrAto = $nrAto;
        return $this;
    }

    public function validator($directorType){
        $validator = Validation::createValidator();
        $violations = $validator->validate($directorType, [
            new Length(['min' => 11]),
            new NotBlank(),
        ]);
    
        return $violations;
    }

}

