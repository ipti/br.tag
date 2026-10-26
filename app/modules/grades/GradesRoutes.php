<?php

/*
 * ARQUIVO GERADO AUTOMATICAMENTE
 * ================================
 * Gerado por: scripts/generate-routes.php
 * Comando:    composer run routes:generate
 *
 * NÃO EDITE ESTE ARQUIVO MANUALMENTE.
 * Qualquer alteração será sobrescrita na próxima geração.
 *
 * Para adicionar ou renomear rotas, altere os controllers correspondentes
 * e re-execute: composer run routes:generate -- grades
 */

class GradesRoutes
{
    // ConceptController
    public const CONCEPT_CREATE = 'grades/concept/create';
    public const CONCEPT_DELETE = 'grades/concept/delete';
    public const CONCEPT_INDEX = 'grades/concept/index';
    public const CONCEPT_UPDATE = 'grades/concept/update';

    // DefaultController
    public const DEFAULT_BATCHCLASSCLOSE = 'grades/default/batchClassClose';
    public const DEFAULT_CALCULATEFINALMEDIA = 'grades/default/calculateFinalMedia';
    public const DEFAULT_CLASSCLOSURE = 'grades/default/classClosure';
    public const DEFAULT_CLASSCLOSURELIST = 'grades/default/classClosureList';
    public const DEFAULT_GETCLASSROOMSTAGES = 'grades/default/getClassroomStages';
    public const DEFAULT_GETDISCIPLINES = 'grades/default/getDisciplines';
    public const DEFAULT_GETGRADES = 'grades/default/getGrades';
    public const DEFAULT_GETGRADESRELEASE = 'grades/default/getGradesRelease';
    public const DEFAULT_GETMODALITIES = 'grades/default/getModalities';
    public const DEFAULT_GETREPORTCARDGRADES = 'grades/default/getReportCardGrades';
    public const DEFAULT_GETUNITIES = 'grades/default/getUnities';
    public const DEFAULT_GRADES = 'grades/default/grades';
    public const DEFAULT_GRADESRELEASE = 'grades/default/gradesRelease';
    public const DEFAULT_REPORTCARD = 'grades/default/reportCard';
    public const DEFAULT_SAVEGRADES = 'grades/default/saveGrades';
    public const DEFAULT_SAVEGRADESRELEASE = 'grades/default/saveGradesRelease';
    public const DEFAULT_SAVEGRADESREPORTCARD = 'grades/default/saveGradesReportCard';

    // EntryController
    public const ENTRY_CALCULATEFINALMEDIA = 'grades/entry/calculateFinalMedia';
    public const ENTRY_GETDISCIPLINES = 'grades/entry/getDisciplines';
    public const ENTRY_GETGRADES = 'grades/entry/getGrades';
    public const ENTRY_GRADES = 'grades/entry/grades';
    public const ENTRY_GRADESRELEASE = 'grades/entry/gradesRelease';
    public const ENTRY_REPORTCARD = 'grades/entry/reportCard';
    public const ENTRY_SAVEGRADES = 'grades/entry/saveGrades';
    public const ENTRY_SAVEGRADESREPORTCARD = 'grades/entry/saveGradesReportCard';

    // StructureController
    public const STRUCTURE_COPY = 'grades/structure/copy';
    public const STRUCTURE_CREATE = 'grades/structure/create';
    public const STRUCTURE_DELETE = 'grades/structure/delete';
    public const STRUCTURE_GETUNITIES = 'grades/structure/getunities';
    public const STRUCTURE_INDEX = 'grades/structure/index';
    public const STRUCTURE_SAVEUNITIES = 'grades/structure/saveunities';

    public static function url(string $route, array $params = []): string
    {
        return Yii::app()->createUrl($route, $params);
    }
}
