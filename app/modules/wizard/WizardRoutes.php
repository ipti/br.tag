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
 * e re-execute: composer run routes:generate -- wizard
 */

class WizardRoutes
{
    // ConfigurationController
    public const CONFIGURATION_CLASSROOM = 'wizard/configuration/classroom';
    public const CONFIGURATION_GETSTUDENTS = 'wizard/configuration/getStudents';
    public const CONFIGURATION_INDEX = 'wizard/configuration/index';
    public const CONFIGURATION_SCHOOL = 'wizard/configuration/school';
    public const CONFIGURATION_STUDENT = 'wizard/configuration/student';

    // DefaultController
    public const DEFAULT_INDEX = 'wizard/default/index';

    public static function url(string $route, array $params = []): string
    {
        return Yii::app()->createUrl($route, $params);
    }
}
