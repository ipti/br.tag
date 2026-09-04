<?php
/* @var $this LessonsplanController */
/* @var $lessonPlan MaceteLessonPlan */

$this->setPageTitle('TAG - Editar Plano MACETE');
echo $this->renderPartial('_form', [
    'lessonPlan' => $lessonPlan,
    'stages' => $stages,
    'stageComponents' => $stageComponents,
    'stageComponentDisciplines' => $stageComponentDisciplines,
    'selectedStageIds' => $selectedStageIds,
    'sectionValues' => $sectionValues,
    'resourceValues' => $resourceValues,
    'materialValues' => $materialValues,
    'selectedAbilities' => $selectedAbilities,
    'schoolName' => $schoolName,
    'territoryContext' => $territoryContext,
    'professorName' => $professorName,
], true);
