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
 * e re-execute: composer run routes:generate -- aeerecord
 */

class AeerecordRoutes
{
    // DefaultController
    public const DEFAULT_ADMIN = 'aeerecord/default/admin';
    public const DEFAULT_CHECKSTUDENTAEERECORD = 'aeerecord/default/checkStudentAeeRecord';
    public const DEFAULT_CREATE = 'aeerecord/default/create';
    public const DEFAULT_DELETE = 'aeerecord/default/delete';
    public const DEFAULT_GETAEERECORD = 'aeerecord/default/getAeeRecord';
    public const DEFAULT_GETCLASSROOMSTUDENTS = 'aeerecord/default/getClassroomStudents';
    public const DEFAULT_GETINSTRUCTORCLASSROOMS = 'aeerecord/default/getInstructorClassrooms';
    public const DEFAULT_INDEX = 'aeerecord/default/index';
    public const DEFAULT_UPDATE = 'aeerecord/default/update';
    public const DEFAULT_VIEW = 'aeerecord/default/view';

    // ReportsController
    public const REPORTS_AEERECORDREPORT = 'aeerecord/reports/aeeRecordReport';

    public static function url(string $route, array $params = []): string
    {
        return Yii::app()->createUrl($route, $params);
    }
}
