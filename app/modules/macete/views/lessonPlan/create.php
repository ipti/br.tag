<?php
/* @var $this LessonPlanController */
/* @var $lessonPlan MaceteLessonPlan */

$this->setPageTitle('TAG - Novo Plano MACETE');
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
    'professorName' => $professorName,
], true);
