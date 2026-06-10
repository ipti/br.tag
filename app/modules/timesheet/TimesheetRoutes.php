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
 * e re-execute: composer run routes:generate -- timesheet
 */

class TimesheetRoutes
{
    // TimesheetController
    public const TIMESHEET_ADDSCHEDULE = 'timesheet/timesheet/addSchedule';
    public const TIMESHEET_ADDSUBSTITUTEINSTRUCTORDAY = 'timesheet/timesheet/addSubstituteInstructorDay';
    public const TIMESHEET_CHANGESCHEDULES = 'timesheet/timesheet/changeSchedules';
    public const TIMESHEET_CHANGEUNAVAILABLESCHEDULE = 'timesheet/timesheet/changeUnavailableSchedule';
    public const TIMESHEET_DELETESUBSTITUTEINSTRUCTORDAY = 'timesheet/timesheet/deleteSubstituteInstructorDay';
    public const TIMESHEET_FIXBUGGEDUNAVAILABLEDAYSFOR2024 = 'timesheet/timesheet/fixBuggedUnavailableDaysFor2024';
    public const TIMESHEET_GENERATETIMESHEET = 'timesheet/timesheet/generateTimesheet';
    public const TIMESHEET_GETDISCIPLINES = 'timesheet/timesheet/getDisciplines';
    public const TIMESHEET_GETFREQUENCY = 'timesheet/timesheet/getFrequency';
    public const TIMESHEET_GETINSTRUCTORS = 'timesheet/timesheet/getInstructors';
    public const TIMESHEET_GETTIMESHEET = 'timesheet/timesheet/getTimesheet';
    public const TIMESHEET_INDEX = 'timesheet/timesheet/index';
    public const TIMESHEET_INSTRUCTORS = 'timesheet/timesheet/instructors';
    public const TIMESHEET_REMOVESCHEDULE = 'timesheet/timesheet/removeSchedule';
    public const TIMESHEET_SAVESUBSTITUTEINSTRUCTORDAY = 'timesheet/timesheet/saveSubstituteInstructorDay';
    public const TIMESHEET_SUBSTITUTEINSTRUCTOR = 'timesheet/timesheet/substituteInstructor';

    public static function url(string $route, array $params = []): string
    {
        return Yii::app()->createUrl($route, $params);
    }
}
