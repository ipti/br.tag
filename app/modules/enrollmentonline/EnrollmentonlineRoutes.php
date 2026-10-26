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
 * e re-execute: composer run routes:generate -- enrollmentonline
 */

class EnrollmentonlineRoutes
{
    // DefaultController
    public const DEFAULT_INDEX = 'enrollmentonline/default/index';

    // EnrollmentonlinepreenrollmenteventonlineController
    public const ENROLLMENTONLINEPREENROLLMENTEVENTONLINE_ADMIN = 'enrollmentonline/enrollmentonlinepreenrollmenteventonline/admin';
    public const ENROLLMENTONLINEPREENROLLMENTEVENTONLINE_CREATE = 'enrollmentonline/enrollmentonlinepreenrollmenteventonline/create';
    public const ENROLLMENTONLINEPREENROLLMENTEVENTONLINE_DELETE = 'enrollmentonline/enrollmentonlinepreenrollmenteventonline/delete';
    public const ENROLLMENTONLINEPREENROLLMENTEVENTONLINE_GETPREENROLLMENTSTAGES = 'enrollmentonline/enrollmentonlinepreenrollmenteventonline/getPreEnrollmentStages';
    public const ENROLLMENTONLINEPREENROLLMENTEVENTONLINE_INDEX = 'enrollmentonline/enrollmentonlinepreenrollmenteventonline/index';
    public const ENROLLMENTONLINEPREENROLLMENTEVENTONLINE_UPDATE = 'enrollmentonline/enrollmentonlinepreenrollmenteventonline/update';
    public const ENROLLMENTONLINEPREENROLLMENTEVENTONLINE_VIEW = 'enrollmentonline/enrollmentonlinepreenrollmenteventonline/view';

    // EnrollmentonlinestudentidentificationController
    public const ENROLLMENTONLINESTUDENTIDENTIFICATION_ADMIN = 'enrollmentonline/enrollmentonlinestudentidentification/admin';
    public const ENROLLMENTONLINESTUDENTIDENTIFICATION_CONFIRMENROLLMENT = 'enrollmentonline/enrollmentonlinestudentidentification/confirmEnrollment';
    public const ENROLLMENTONLINESTUDENTIDENTIFICATION_CREATE = 'enrollmentonline/enrollmentonlinestudentidentification/create';
    public const ENROLLMENTONLINESTUDENTIDENTIFICATION_DELETE = 'enrollmentonline/enrollmentonlinestudentidentification/delete';
    public const ENROLLMENTONLINESTUDENTIDENTIFICATION_GETCITIES = 'enrollmentonline/enrollmentonlinestudentidentification/getCities';
    public const ENROLLMENTONLINESTUDENTIDENTIFICATION_GETSCHOOLS = 'enrollmentonline/enrollmentonlinestudentidentification/getSchools';
    public const ENROLLMENTONLINESTUDENTIDENTIFICATION_INDEX = 'enrollmentonline/enrollmentonlinestudentidentification/index';
    public const ENROLLMENTONLINESTUDENTIDENTIFICATION_REJECTENROLLMENT = 'enrollmentonline/enrollmentonlinestudentidentification/rejectEnrollment';
    public const ENROLLMENTONLINESTUDENTIDENTIFICATION_RENDERSTUDENTTABLE = 'enrollmentonline/enrollmentonlinestudentidentification/renderStudentTable';
    public const ENROLLMENTONLINESTUDENTIDENTIFICATION_STUDENTLIST = 'enrollmentonline/enrollmentonlinestudentidentification/studentList';
    public const ENROLLMENTONLINESTUDENTIDENTIFICATION_UPDATE = 'enrollmentonline/enrollmentonlinestudentidentification/update';

    public static function url(string $route, array $params = []): string
    {
        return Yii::app()->createUrl($route, $params);
    }
}
