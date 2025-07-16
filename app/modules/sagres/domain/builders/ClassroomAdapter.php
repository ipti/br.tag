<?php

use SagresEdu\TurmaTType;
class ClassroomAdapter
{
    private $extractor;

    private array $classrooms;
    public function __construct(ClassroomExtractor $extractor)
    {
        $this->extractor = $extractor;
        $this->classrooms = [];
    }
    /**
     * @return TurmaTType[]
     */
    public function parse()
    {

        $raw = $this->extractor->execute();
        foreach ($raw as $classroom) {
            $classroom_sagres = new TurmaTType();

            /*lançar erro aqui com base no valor do atributo*/
            $stage = $classroom->getAttribute('edcenso_associated_stage_id') !== null ? $classroom->getAttribute('edcenso_associated_stage_id') : $classroom->getAttribute("edcenso_stage_vs_modality_fk");


            $series = $this->loadSerie($classroom->getAttribute("id"));

            $classroom_sagres->setDescricao($classroom->name);
            array_push($this->classrooms, $classroom_sagres);
        }
        return $this->classrooms;
    }

    private function loadSerie($classId)
    {

        return [];
    }
}
?>