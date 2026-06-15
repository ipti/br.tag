<?php
/* @var $this LessonPlanController */
/* @var $lessonPlan MaceteLessonPlan */

$this->setPageTitle('TAG - Editar Plano MACETE');
echo $this->renderPartial('_form', [
    'lessonPlan' => $lessonPlan,
    'stages' => $stages,
    'disciplines' => $disciplines,
    'classrooms' => $classrooms,
    'sectionValues' => $sectionValues,
    'resourceValues' => $resourceValues,
    'materialValues' => $materialValues,
    'selectedAbilities' => $selectedAbilities,
], true);

