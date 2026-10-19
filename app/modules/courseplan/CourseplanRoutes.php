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
 * e re-execute: composer run routes:generate -- courseplan
 */

class CourseplanRoutes
{
    // CourseplanController
    public const COURSEPLAN_ADDRESOURCES = 'courseplan/courseplan/addResources';
    public const COURSEPLAN_CHECKRESOURCEEXISTS = 'courseplan/courseplan/checkResourceExists';
    public const COURSEPLAN_CREATE = 'courseplan/courseplan/create';
    public const COURSEPLAN_DELETEPLAN = 'courseplan/courseplan/deletePlan';
    public const COURSEPLAN_ENABLECOURSEPLANEDITION = 'courseplan/courseplan/enableCoursePlanEdition';
    public const COURSEPLAN_GETABILITIES = 'courseplan/courseplan/getAbilities';
    public const COURSEPLAN_GETABILITIESINITIALSTRUCTURE = 'courseplan/courseplan/getAbilitiesInitialStructure';
    public const COURSEPLAN_GETABILITIESNEXTSTRUCTURE = 'courseplan/courseplan/getAbilitiesNextStructure';
    public const COURSEPLAN_GETCOURSECLASSES = 'courseplan/courseplan/getCourseClasses';
    public const COURSEPLAN_GETDISCIPLINES = 'courseplan/courseplan/getDisciplines';
    public const COURSEPLAN_GETRESOURCES = 'courseplan/courseplan/getResources';
    public const COURSEPLAN_INDEX = 'courseplan/courseplan/index';
    public const COURSEPLAN_PENDINGPLANS = 'courseplan/courseplan/pendingPlans';
    public const COURSEPLAN_SAVE = 'courseplan/courseplan/save';
    public const COURSEPLAN_UPDATE = 'courseplan/courseplan/update';
    public const COURSEPLAN_VALIDATEPLAN = 'courseplan/courseplan/validatePlan';

    // DefaultController
    public const DEFAULT_ADDRESOURCES = 'courseplan/default/addResources';
    public const DEFAULT_CREATE = 'courseplan/default/create';
    public const DEFAULT_DELETE = 'courseplan/default/delete';
    public const DEFAULT_GETABILITIESINITIALSTRUCTURE = 'courseplan/default/getAbilitiesInitialStructure';
    public const DEFAULT_GETABILITIESNEXTSTRUCTURE = 'courseplan/default/getAbilitiesNextStructure';
    public const DEFAULT_GETCOURSECLASSES = 'courseplan/default/getCourseClasses';
    public const DEFAULT_GETDISCIPLINES = 'courseplan/default/getDisciplines';
    public const DEFAULT_GETRESOURCES = 'courseplan/default/getResources';
    public const DEFAULT_INDEX = 'courseplan/default/index';
    public const DEFAULT_PENDINGPLANS = 'courseplan/default/pendingPlans';
    public const DEFAULT_SAVE = 'courseplan/default/save';
    public const DEFAULT_UPDATE = 'courseplan/default/update';

    public static function url(string $route, array $params = []): string
    {
        return Yii::app()->createUrl($route, $params);
    }
}
