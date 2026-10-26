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
 * e re-execute: composer run routes:generate -- calendar
 */

class CalendarRoutes
{
    // DefaultController
    public const DEFAULT_CHANGEEVENT = 'calendar/default/changeEvent';
    public const DEFAULT_CREATE = 'calendar/default/create';
    public const DEFAULT_DELETEEVENT = 'calendar/default/deleteEvent';
    public const DEFAULT_EDITCALENDAR = 'calendar/default/editCalendar';
    public const DEFAULT_EDITUNITYPERIODS = 'calendar/default/editUnityPeriods';
    public const DEFAULT_EVENT = 'calendar/default/event';
    public const DEFAULT_INDEX = 'calendar/default/index';
    public const DEFAULT_LOADCALENDARDATA = 'calendar/default/loadCalendarData';
    public const DEFAULT_LOADUNITYPERIODS = 'calendar/default/loadUnityPeriods';
    public const DEFAULT_REMOVECALENDAR = 'calendar/default/removeCalendar';
    public const DEFAULT_SHOWSTAGES = 'calendar/default/showStages';
    public const DEFAULT_VIEWPERIODS = 'calendar/default/viewPeriods';

    public static function url(string $route, array $params = []): string
    {
        return Yii::app()->createUrl($route, $params);
    }
}
