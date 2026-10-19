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
 * e re-execute: composer run routes:generate -- root
 */

class AppRoutes
{
    // AdministratorController
    public const ADMINISTRATOR_ACL = 'administrator/aCL';
    public const ADMINISTRATOR_CLEANMASTER = 'administrator/cleanMaster';
    public const ADMINISTRATOR_CREATEUSER = 'administrator/createUser';
    public const ADMINISTRATOR_DATA = 'administrator/data';
    public const ADMINISTRATOR_DOWNLOADEXPORTFILE = 'administrator/downloadExportFile';
    public const ADMINISTRATOR_EXPORT = 'administrator/export';
    public const ADMINISTRATOR_EXPORTSTUDENTIDENTIFY = 'administrator/exportStudentIdentify';
    public const ADMINISTRATOR_EXPORTTOMASTER = 'administrator/exportToMaster';
    public const ADMINISTRATOR_IMPORT = 'administrator/import';
    public const ADMINISTRATOR_IMPORTFROMMASTER = 'administrator/importFromMaster';
    public const ADMINISTRATOR_INDEX = 'administrator/index';
    public const ADMINISTRATOR_LOADTOMASTER = 'administrator/loadToMaster';
    public const ADMINISTRATOR_MULTISTAGECLASSROOMVERIFY = 'administrator/multiStageClassroomVerify';
    public const ADMINISTRATOR_SAVEMULTISTAGE = 'administrator/saveMultiStage';
    public const ADMINISTRATOR_SYNCEXPORT = 'administrator/syncExport';
    public const ADMINISTRATOR_SYNCIMPORT = 'administrator/syncImport';
    public const ADMINISTRATOR_UPDATEDB = 'administrator/updateDB';

    // CensoController
    public const CENSO_DOWNLOADEXPORTFILE = 'censo/downloadExportFile';
    public const CENSO_DOWNLOADEXPORTFILEIDENTIFICATION = 'censo/downloadExportFileIdentification';
    public const CENSO_EXPORT = 'censo/export';
    public const CENSO_EXPORTIDENTIFICATION = 'censo/exportIdentification';
    public const CENSO_EXPORTWITHOUTINEPID = 'censo/exportWithoutInepid';
    public const CENSO_IMPORT = 'censo/import';
    public const CENSO_IMPORTDEGREECODES = 'censo/importDegreeCodes';
    public const CENSO_INDEX = 'censo/index';
    public const CENSO_INEPIMPORT = 'censo/inepImport';
    public const CENSO_INITIALIMPORT = 'censo/initialImport';
    public const CENSO_VALIDATE = 'censo/validate';

    // SiteController
    public const SITE_CHANGESCHOOL = 'site/changeSchool';
    public const SITE_CHANGEYEAR = 'site/changeYear';
    public const SITE_CONTACT = 'site/contact';
    public const SITE_DOWNLOADFILELOG = 'site/downloadFileLog';
    public const SITE_ERROR = 'site/error';
    public const SITE_INDEX = 'site/index';
    public const SITE_LOADCYLINDERCHARTDATA = 'site/loadCylinderChartData';
    public const SITE_LOADLINECHARTDATA = 'site/loadLineChartData';
    public const SITE_LOADMORELOGS = 'site/loadMoreLogs';
    public const SITE_LOADPIECHARTDATA = 'site/loadPieChartData';
    public const SITE_LOADSCHOOLSUMMARY = 'site/loadSchoolSummary';
    public const SITE_LOADWARNSHTML = 'site/loadWarnsHtml';
    public const SITE_LOGIN = 'site/login';
    public const SITE_LOGOUT = 'site/logout';
    public const SITE_VIEWFILELOGS = 'site/viewFileLogs';

    public static function url(string $route, array $params = []): string
    {
        return Yii::app()->createUrl($route, $params);
    }
}
