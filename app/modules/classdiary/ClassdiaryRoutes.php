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
 * e re-execute: composer run routes:generate -- classdiary
 */

class ClassdiaryRoutes
{
    // ClassesController
    public const CLASSES_CLASSCONTENTS = 'classdiary/classes/classContents';
    public const CLASSES_FREQUENCY = 'classdiary/classes/frequency';
    public const CLASSES_GETCLASSCONTENTS = 'classdiary/classes/getClassContents';
    public const CLASSES_GETDISCIPLINES = 'classdiary/classes/getDisciplines';
    public const CLASSES_GETFREQUENCY = 'classdiary/classes/getFrequency';
    public const CLASSES_GETMONTHSANDDISCIPLINES = 'classdiary/classes/getMonthsAndDisciplines';
    public const CLASSES_SAVECLASSCONTENTS = 'classdiary/classes/saveClassContents';
    public const CLASSES_SAVEFREQUENCIES = 'classdiary/classes/saveFrequencies';
    public const CLASSES_SAVEFREQUENCY = 'classdiary/classes/saveFrequency';
    public const CLASSES_SAVEJUSTIFICATION = 'classdiary/classes/saveJustification';
    public const CLASSES_SAVEJUSTIFICATIONS = 'classdiary/classes/saveJustifications';
    public const CLASSES_VALIDATECLASSCONTENTS = 'classdiary/classes/validateClassContents';

    // ClassFaultsController
    public const CLASSFAULTS_ADMIN = 'classdiary/classFaults/admin';
    public const CLASSFAULTS_CREATE = 'classdiary/classFaults/create';
    public const CLASSFAULTS_DELETE = 'classdiary/classFaults/delete';
    public const CLASSFAULTS_INDEX = 'classdiary/classFaults/index';
    public const CLASSFAULTS_UPDATE = 'classdiary/classFaults/update';
    public const CLASSFAULTS_VIEW = 'classdiary/classFaults/view';

    // DefaultController
    public const DEFAULT_CLASSDIARY = 'classdiary/default/classDiary';
    public const DEFAULT_GETCLASSESCONTENTS = 'classdiary/default/getClassesContents';
    public const DEFAULT_GETCLASSROOMS = 'classdiary/default/getClassrooms';
    public const DEFAULT_GETDATES = 'classdiary/default/getDates';
    public const DEFAULT_GETMONTHS = 'classdiary/default/getMonths';
    public const DEFAULT_INDEX = 'classdiary/default/index';
    public const DEFAULT_RENDERACCORDION = 'classdiary/default/renderAccordion';
    public const DEFAULT_RENDERFREQUENCYELEMENTDESKTOP = 'classdiary/default/renderFrequencyElementDesktop';
    public const DEFAULT_RENDERFREQUENCYELEMENTMOBILE = 'classdiary/default/renderFrequencyElementMobile';
    public const DEFAULT_SAVECLASSCONTENTS = 'classdiary/default/saveClassContents';
    public const DEFAULT_SAVEFRESQUENCY = 'classdiary/default/saveFresquency';
    public const DEFAULT_STUDENTCLASSDIARY = 'classdiary/default/studentClassDiary';

    public static function url(string $route, array $params = []): string
    {
        return Yii::app()->createUrl($route, $params);
    }
}
