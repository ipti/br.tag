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
 * e re-execute: composer run routes:generate -- classroom
 */

class ClassroomRoutes
{
    // DefaultController
    public const DEFAULT_ADMIN = 'classroom/default/admin';
    public const DEFAULT_BATCHUPDATE = 'classroom/default/batchupdate';
    public const DEFAULT_BATCHUPDATENROLLMENT = 'classroom/default/batchupdatEnrollment';
    public const DEFAULT_BATCHUPDATETOTAL = 'classroom/default/batchUpdateTotal';
    public const DEFAULT_BATCHUPDATETRANSPORT = 'classroom/default/batchUpdateTransport';
    public const DEFAULT_CHANGEENROLLMENTS = 'classroom/default/changeEnrollments';
    public const DEFAULT_CREATE = 'classroom/default/create';
    public const DEFAULT_DELETE = 'classroom/default/delete';
    public const DEFAULT_GETASSISTANCETYPE = 'classroom/default/getAssistanceType';
    public const DEFAULT_GETGRADESRULESCLASSROOM = 'classroom/default/getGradesRulesClassroom';
    public const DEFAULT_INDEX = 'classroom/default/index';
    public const DEFAULT_SYNCTOSEDSP = 'classroom/default/syncToSedsp';
    public const DEFAULT_SYNCUNSYNCEDSTUDENTS = 'classroom/default/syncUnsyncedStudents';
    public const DEFAULT_UPDATE = 'classroom/default/update';
    public const DEFAULT_UPDATEASSISTANCETYPEDEPENDENCIES = 'classroom/default/updateAssistanceTypeDependencies';
    public const DEFAULT_UPDATECOMPLEMENTARYACTIVITY = 'classroom/default/updateComplementaryActivity';
    public const DEFAULT_UPDATEDAILYORDER = 'classroom/default/updateDailyOrder';
    public const DEFAULT_UPDATEDISCIPLINESANDCALENDARS = 'classroom/default/updateDisciplinesAndCalendars';
    public const DEFAULT_UPDATETIME = 'classroom/default/updateTime';
    public const DEFAULT_VIEW = 'classroom/default/view';

    public static function url(string $route, array $params = []): string
    {
        return Yii::app()->createUrl($route, $params);
    }
}
