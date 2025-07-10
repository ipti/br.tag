<?php
class SagresExporter
{
    private $referenceYear;
    public function run()
    {
        $school_extractor = new SchoolExtractor();
        $schools = $school_extractor->execute();

        foreach ($schools as $key => $school) {
            $classroom_extractor = new ClassroomExtractor($school->inep_id, $this->referenceYear);
            $classrooms = $classroom_extractor->execute();
        }
    }
}
?>