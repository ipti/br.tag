<?php

namespace SagresEdu;

use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validation;

/**
 * Class representing DiretorTType.
 *
 * XSD Type: diretor_t
 */
class DiretorTType
{
    #[Serializer\SerializedName('edu:cpfDiretor')]
    #[Serializer\XmlElement(cdata: false)]
    private $cpfDiretor = null;

    #[Serializer\SerializedName('edu:nrAto')]
    #[Serializer\XmlElement(cdata: false)]
    private $nrAto = null;

    public function getCpfDiretor(): ?string
    {
        return $this->cpfDiretor;
    }

    public function setCpfDiretor(string $cpfDiretor): self
    {
        $this->cpfDiretor = $cpfDiretor;

        return $this;
    }

    public function getNrAto(): ?string
    {
        return $this->nrAto;
    }

    public function setNrAto(string $nrAto): self
    {
        $this->nrAto = $nrAto;

        return $this;
    }

    public function validator($directorType)
    {
        $validator = Validation::createValidator();
        $violations = $validator->validate($directorType, [
            new Length(['min' => 11]),
            new NotBlank(),
        ]);

        return $violations;
    }
}
