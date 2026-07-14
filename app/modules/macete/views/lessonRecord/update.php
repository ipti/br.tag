<?php
/* @var $this LessonRecordController */
/* @var $lessonRecord MaceteLessonRecord */

$this->setPageTitle('TAG - Editar Registro MACETE');
echo $this->renderPartial('_form', [
    'lessonRecord' => $lessonRecord,
    'plans' => $plans,
    'classrooms' => $classrooms,
    'selectedAbilities' => $selectedAbilities,
], true);

