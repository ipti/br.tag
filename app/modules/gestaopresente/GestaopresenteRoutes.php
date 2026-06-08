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
 * e re-execute: composer run routes:generate -- gestaopresente
 */

class GestaopresenteRoutes
{
    // DefaultController
    public const DEFAULT_EXPORTCHANGEENROLLMENT = 'gestaopresente/default/exportChangeEnrollment';
    public const DEFAULT_EXPORTPERSONALDATA = 'gestaopresente/default/exportPersonalData';
    public const DEFAULT_EXPORTRENROLLMENT = 'gestaopresente/default/exportRenrollment';
    public const DEFAULT_INDEX = 'gestaopresente/default/index';

    public static function url(string $route, array $params = []): string
    {
        return Yii::app()->createUrl($route, $params);
    }
}
