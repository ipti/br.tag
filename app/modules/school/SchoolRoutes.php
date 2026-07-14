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
 * e re-execute: composer run routes:generate -- school
 */

class SchoolRoutes
{
    // SchoolController
    public const SCHOOL_ADMIN = 'school/school/admin';
    public const SCHOOL_CREATE = 'school/school/create';
    public const SCHOOL_CREATEROOM = 'school/school/createRoom';
    public const SCHOOL_DELETE = 'school/school/delete';
    public const SCHOOL_DELETEROOM = 'school/school/deleteRoom';
    public const SCHOOL_DISPLAYLOGO = 'school/school/displayLogo';
    public const SCHOOL_GETCITIES = 'school/school/getCities';
    public const SCHOOL_GETDISTRICTS = 'school/school/getDistricts';
    public const SCHOOL_GETMANAGERCITIES = 'school/school/getManagerCities';
    public const SCHOOL_INDEX = 'school/school/index';
    public const SCHOOL_RECORD = 'school/school/record';
    public const SCHOOL_REMOVELOGO = 'school/school/removeLogo';
    public const SCHOOL_REPORTS = 'school/school/reports';
    public const SCHOOL_REPORTSMONTHLYTRANSACTION = 'school/school/reportsMonthlyTransaction';
    public const SCHOOL_UPDATE = 'school/school/update';
    public const SCHOOL_UPDATECITYDEPENDENCIES = 'school/school/updateCityDependencies';
    public const SCHOOL_UPDATEROOM = 'school/school/updateRoom';
    public const SCHOOL_UPDATEUFDEPENDENCIES = 'school/school/updateUfDependencies';
    public const SCHOOL_VIEW = 'school/school/view';

    public static function url(string $route, array $params = []): string
    {
        return Yii::app()->createUrl($route, $params);
    }
}
