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
 * e re-execute: composer run routes:generate -- resultsmanagement
 */

class ResultsmanagementRoutes
{
    // DefaultController
    public const DEFAULT_GETGMAPINFO = 'resultsmanagement/default/getGMapInfo';
    public const DEFAULT_INDEX = 'resultsmanagement/default/index';

    // ManagementschoolController
    public const MANAGEMENTSCHOOL_FREQUENCY = 'resultsmanagement/managementschool/frequency';
    public const MANAGEMENTSCHOOL_INDEX = 'resultsmanagement/managementschool/index';
    public const MANAGEMENTSCHOOL_LOADCHARTDATA = 'resultsmanagement/managementschool/loadChartData';
    public const MANAGEMENTSCHOOL_LOADCLASSROOMINFOS = 'resultsmanagement/managementschool/loadClassroomInfos';
    public const MANAGEMENTSCHOOL_LOADDATAFOREVOLUTION = 'resultsmanagement/managementschool/loadDataForEvolution';
    public const MANAGEMENTSCHOOL_LOADDATAFORPROFICIENCY = 'resultsmanagement/managementschool/loadDataForProficiency';
    public const MANAGEMENTSCHOOL_LOADDISCIPLINEINFO = 'resultsmanagement/managementschool/loadDisciplineInfo';
    public const MANAGEMENTSCHOOL_LOADPERFORMANCECHARTDATA = 'resultsmanagement/managementschool/loadPerformanceChartData';
    public const MANAGEMENTSCHOOL_LOADPERFORMANCECLASSROOMINFOS = 'resultsmanagement/managementschool/loadPerformanceClassroomInfos';
    public const MANAGEMENTSCHOOL_PERFORMANCE = 'resultsmanagement/managementschool/performance';

    public static function url(string $route, array $params = []): string
    {
        return Yii::app()->createUrl($route, $params);
    }
}
