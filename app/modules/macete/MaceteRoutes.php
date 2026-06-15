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
 * e re-execute: composer run routes:generate -- macete
 */

class MaceteRoutes
{
    // AbilityController
    public const ABILITY_INITIALSTRUCTURE = 'macete/ability/initialStructure';
    public const ABILITY_NEXTSTRUCTURE = 'macete/ability/nextStructure';
    public const ABILITY_SEARCH = 'macete/ability/search';

    // LessonPlanController
    public const LESSONPLAN_CREATE = 'macete/lessonPlan/create';
    public const LESSONPLAN_DELETE = 'macete/lessonPlan/delete';
    public const LESSONPLAN_GETDISCIPLINES = 'macete/lessonPlan/getDisciplines';
    public const LESSONPLAN_GETPLAN = 'macete/lessonPlan/getPlan';
    public const LESSONPLAN_INDEX = 'macete/lessonPlan/index';
    public const LESSONPLAN_UPDATE = 'macete/lessonPlan/update';

    // LessonRecordController
    public const LESSONRECORD_CREATE = 'macete/lessonRecord/create';
    public const LESSONRECORD_DELETE = 'macete/lessonRecord/delete';
    public const LESSONRECORD_INDEX = 'macete/lessonRecord/index';
    public const LESSONRECORD_UPDATE = 'macete/lessonRecord/update';

    public static function url(string $route, array $params = []): string
    {
        return Yii::app()->createUrl($route, $params);
    }
}
