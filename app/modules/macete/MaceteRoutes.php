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

    // LessonsplanController
    public const LESSONSPLAN_CREATE = 'macete/lessonsplan/create';
    public const LESSONSPLAN_DELETE = 'macete/lessonsplan/delete';
    public const LESSONSPLAN_GETDISCIPLINES = 'macete/lessonsplan/getDisciplines';
    public const LESSONSPLAN_GETPLAN = 'macete/lessonsplan/getPlan';
    public const LESSONSPLAN_INDEX = 'macete/lessonsplan/index';
    public const LESSONSPLAN_UPDATE = 'macete/lessonsplan/update';

    // LessonsrecordController
    public const LESSONSRECORD_CREATE = 'macete/lessonsrecord/create';
    public const LESSONSRECORD_DELETE = 'macete/lessonsrecord/delete';
    public const LESSONSRECORD_INDEX = 'macete/lessonsrecord/index';
    public const LESSONSRECORD_UPDATE = 'macete/lessonsrecord/update';

    public static function url(string $route, array $params = []): string
    {
        return Yii::app()->createUrl($route, $params);
    }
}
