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

    // DiaryController
    public const DIARY_GETDISCIPLINES = 'macete/diary/getDisciplines';
    public const DIARY_GETSUMMARY = 'macete/diary/getSummary';
    public const DIARY_INDEX = 'macete/diary/index';

    // LessonsplanController
    public const LESSONSPLAN_CREATE = 'macete/lessonsplan/create';
    public const LESSONSPLAN_DELETE = 'macete/lessonsplan/delete';
    public const LESSONSPLAN_GETDISCIPLINES = 'macete/lessonsplan/getDisciplines';
    public const LESSONSPLAN_GETPLAN = 'macete/lessonsplan/getPlan';
    public const LESSONSPLAN_GETTEMPLATES = 'macete/lessonsplan/getTemplates';
    public const LESSONSPLAN_INDEX = 'macete/lessonsplan/index';
    public const LESSONSPLAN_UPDATE = 'macete/lessonsplan/update';

    // LessonsrecordController
    public const LESSONSRECORD_CREATE = 'macete/lessonsrecord/create';
    public const LESSONSRECORD_DELETE = 'macete/lessonsrecord/delete';
    public const LESSONSRECORD_GETDAYS = 'macete/lessonsrecord/getDays';
    public const LESSONSRECORD_GETMONTHS = 'macete/lessonsrecord/getMonths';
    public const LESSONSRECORD_INDEX = 'macete/lessonsrecord/index';
    public const LESSONSRECORD_UPDATE = 'macete/lessonsrecord/update';

    // TemplatesController
    public const TEMPLATES_CREATE = 'macete/templates/create';
    public const TEMPLATES_DELETE = 'macete/templates/delete';
    public const TEMPLATES_GETDISCIPLINES = 'macete/templates/getDisciplines';
    public const TEMPLATES_INDEX = 'macete/templates/index';
    public const TEMPLATES_UPDATE = 'macete/templates/update';

    public static function url(string $route, array $params = []): string
    {
        return Yii::app()->createUrl($route, $params);
    }
}
