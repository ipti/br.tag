<?php

use SagresEdu\TurmaTType;
class ClassroomAdapter
{
    private $extractor;

    private array $classrooms;
    public function __construct(ClassroomExtractor $extractor)
    {
        $this->extractor = $extractor;
    }
    /**
     * @return TurmaTType[]
     */
    public function parse()
    {

        $raw = $this->extractor->execute();
        foreach ($raw as $classroom) {
            $classroom_sagres = new TurmaTType();
            $classroom_sagres->setDescricao($classroom->name);
            array_push($this->classrooms, $classroom_sagres);
        }
        return $this->classrooms;
    }

    private function loadSerie(){
        
    }
}
?>