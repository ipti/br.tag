<?php
    Yii::import('application.modules.classdiary.services.*');
     /**
    * @property GetDiscipline $GetDiscipline
    */
    class GetDiscipline
    {
        private $disciplineService;

        public function __construct($disciplineService = null)
        {
            $this->disciplineService = $disciplineService ?? new DisciplineService();
        }
        public function exec($discipline_fk){
                $response = $this->disciplineService->getDiscipline($discipline_fk);
                return  $response; 
        }
    }