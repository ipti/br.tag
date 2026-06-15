<?php
/* @var $this LessonRecordController */
/* @var $lessonRecord MaceteLessonRecord */

$this->setPageTitle('TAG - Registrar Aula MACETE');
echo $this->renderPartial('_form', [
    'lessonRecord' => $lessonRecord,
    'plans' => $plans,
    'classrooms' => $classrooms,
    'selectedAbilities' => $selectedAbilities,
], true);

