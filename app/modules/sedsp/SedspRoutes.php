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
 * e re-execute: composer run routes:generate -- sedsp
 */

class SedspRoutes
{
    // DefaultController
    public const DEFAULT_ADDCLASSROOM = 'sedsp/default/addClassroom';
    public const DEFAULT_ADDSCHOOL = 'sedsp/default/addSchool';
    public const DEFAULT_ADDSTUDENTWITHRA = 'sedsp/default/addStudentWithRA';
    public const DEFAULT_CREATE = 'sedsp/default/create';
    public const DEFAULT_CREATERA = 'sedsp/default/createRA';
    public const DEFAULT_GENERATERA = 'sedsp/default/generateRA';
    public const DEFAULT_IMPORTCLASSROOMFROMSEDSP = 'sedsp/default/importClassroomFromSedsp';
    public const DEFAULT_IMPORTFULLSCHOOL = 'sedsp/default/importFullSchool';
    public const DEFAULT_IMPORTFULLSTUDENTSBYCLASSES = 'sedsp/default/importFullStudentsByClasses';
    public const DEFAULT_IMPORTSTUDENTRA = 'sedsp/default/importStudentRA';
    public const DEFAULT_INDEX = 'sedsp/default/index';
    public const DEFAULT_LOGIN = 'sedsp/default/login';
    public const DEFAULT_MANAGERA = 'sedsp/default/manageRA';
    public const DEFAULT_SYNCSCHOOLCLASSROOMS = 'sedsp/default/syncSchoolClassrooms';
    public const DEFAULT_UPDATESTUDENTFROMSEDSP = 'sedsp/default/updateStudentFromSedsp';

    public static function url(string $route, array $params = []): string
    {
        return Yii::app()->createUrl($route, $params);
    }
}
