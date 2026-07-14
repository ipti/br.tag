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
 * e re-execute: composer run routes:generate -- systemadmin
 */

class SystemadminRoutes
{
    // DefaultController
    public const DEFAULT_ACTIVEDISABLEUSER = 'systemadmin/default/activeDisableUser';
    public const DEFAULT_ACTIVEUSER = 'systemadmin/default/activeUser';
    public const DEFAULT_AUDITORY = 'systemadmin/default/auditory';
    public const DEFAULT_CHANGELOG = 'systemadmin/default/changelog';
    public const DEFAULT_CREATEUSER = 'systemadmin/default/createUser';
    public const DEFAULT_DELETEUSER = 'systemadmin/default/deleteUser';
    public const DEFAULT_DISABLEUSER = 'systemadmin/default/disableUser';
    public const DEFAULT_EDITINSTANCECONFIGS = 'systemadmin/default/editInstanceConfigs';
    public const DEFAULT_EDITMANAGEMODULES = 'systemadmin/default/editManageModules';
    public const DEFAULT_EDITPASSWORD = 'systemadmin/default/editPassword';
    public const DEFAULT_EXPORTCOUNTUSERS = 'systemadmin/default/exportCountUsers';
    public const DEFAULT_EXPORTFAULTS = 'systemadmin/default/exportFaults';
    public const DEFAULT_EXPORTGRADES = 'systemadmin/default/exportGrades';
    public const DEFAULT_EXPORTMASTER = 'systemadmin/default/exportMaster';
    public const DEFAULT_EXPORTS = 'systemadmin/default/exports';
    public const DEFAULT_EXPORTSTUDENTS = 'systemadmin/default/exportStudents';
    public const DEFAULT_GETAUDITORYLOGS = 'systemadmin/default/getAuditoryLogs';
    public const DEFAULT_IMPORTBNCC = 'systemadmin/default/importBNCC';
    public const DEFAULT_IMPORTMASTER = 'systemadmin/default/importMaster';
    public const DEFAULT_INDEX = 'systemadmin/default/index';
    public const DEFAULT_INSTANCECONFIG = 'systemadmin/default/instanceConfig';
    public const DEFAULT_MANAGEMODULES = 'systemadmin/default/manageModules';
    public const DEFAULT_MANAGEUSERS = 'systemadmin/default/manageUsers';
    public const DEFAULT_PHPCONFIG = 'systemadmin/default/pHPConfig';
    public const DEFAULT_RESTORERBAC = 'systemadmin/default/restoreRBAC';
    public const DEFAULT_UPDATE = 'systemadmin/default/update';

    public static function url(string $route, array $params = []): string
    {
        return Yii::app()->createUrl($route, $params);
    }
}
