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
 * e re-execute: composer run routes:generate -- instructor
 */

class InstructorRoutes
{
    // DefaultController
    public const DEFAULT_ADMIN = 'instructor/default/admin';
    public const DEFAULT_CREATE = 'instructor/default/create';
    public const DEFAULT_DELETE = 'instructor/default/delete';
    public const DEFAULT_FREQUENCY = 'instructor/default/frequency';
    public const DEFAULT_GETCITY = 'instructor/default/getCity';
    public const DEFAULT_GETCITYBYCEP = 'instructor/default/getCityByCep';
    public const DEFAULT_GETCLASSROOMS = 'instructor/default/getClassrooms';
    public const DEFAULT_GETCOURSES = 'instructor/default/getCourses';
    public const DEFAULT_GETFREQUENCY = 'instructor/default/getFrequency';
    public const DEFAULT_GETFREQUENCYCLASSROOM = 'instructor/default/getFrequencyClassroom';
    public const DEFAULT_GETFREQUENCYDISCIPLINES = 'instructor/default/getFrequencyDisciplines';
    public const DEFAULT_GETINSTITUTION = 'instructor/default/getInstitution';
    public const DEFAULT_GETINSTITUTIONS = 'instructor/default/getInstitutions';
    public const DEFAULT_INDEX = 'instructor/default/index';
    public const DEFAULT_PRINTHISTORY = 'instructor/default/printHistory';
    public const DEFAULT_PRINTYEARHISTORY = 'instructor/default/printYearHistory';
    public const DEFAULT_SAVEFREQUENCY = 'instructor/default/saveFrequency';
    public const DEFAULT_SAVEJUSTIFICATION = 'instructor/default/saveJustification';
    public const DEFAULT_UPDATE = 'instructor/default/update';
    public const DEFAULT_UPDATEEMAILS = 'instructor/default/updateEmails';
    public const DEFAULT_VIEW = 'instructor/default/view';

    public static function url(string $route, array $params = []): string
    {
        return Yii::app()->createUrl($route, $params);
    }
}
