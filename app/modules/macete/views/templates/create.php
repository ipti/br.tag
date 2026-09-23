<?php
/* @var $this TemplatesController */
/* @var $template MaceteLessonPlanTemplate */

$this->setPageTitle('TAG - Novo Modelo de Plano MACETE');
echo $this->renderPartial('_form', [
    'template' => $template,
    'stages' => $stages,
    'stageComponents' => $stageComponents,
    'stageComponentDisciplines' => $stageComponentDisciplines,
    'selectedStageIds' => $selectedStageIds,
    'sectionValues' => $sectionValues,
    'resourceValues' => $resourceValues,
    'materialValues' => $materialValues,
    'selectedAbilities' => $selectedAbilities,
], true);
