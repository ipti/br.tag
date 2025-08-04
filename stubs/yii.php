<?php

// Base
class CComponent {}
class CConsoleCommand {
    public function run($args) {}
}
class CModel extends CComponent {}
class CActiveRecord extends CModel {}
class CActiveRecordEvent {}
class CModelEvent {}
class CEvent {}

class CDbCriteria {}
class CDbConnection {}
class CDbCommand {}
class CDbDataReader {}
class CDbColumnSchema {}
class CDbTableSchema {}
class CDbSchema {}
class CDbTransaction {}

// Comportamentos
class CModelBehavior {}
class CActiveRecordBehavior extends CModelBehavior {
    public function afterSave($event) {}
    public function beforeSave($event) {}
    public function afterDelete($event) {}
    public function beforeDelete($event) {}
    public function afterFind($event) {}
    public function beforeFind($event) {}
}

// Validação
class CValidator {}
class CRequiredValidator extends CValidator {}
class CStringValidator extends CValidator {}
class CNumberValidator extends CValidator {}

// Relacionamentos
class CBelongsToRelation {}
class CHasOneRelation {}
class CHasManyRelation {}
class CManyManyRelation {}

// Web
class CController extends CComponent {}
class CWebApplication extends CApplication {}
class CApplication extends CComponent
{
    /** @var TagUtils */
    public static $utils;

    /** @var FeaturesComponent */
    public static $features;

    /** @var CAssetManager */
    public static $assetManager;

    /** @var CWebUser */
    public static $user;

    /** @var CCache */
    public static $cache;

    /** @var CUrlManager */
    public static $urlManager;

    /** @var CDbConnection */
    public static $db2;

    /** @var CDbConnection */
    public static $db;

    /** @var IAuthManager */
    public static $authManager;

    /** @var CErrorHandler */
    public static $errorHandler;

    /** @var Websupport\YiiSentry\Client */
    public static $sentry;

    /** @var CLogRouter */
    public $log;
}
class CWebUser {}
class CHtml {}
class CActiveForm {}
class CClientScript {}

// Coleções
class CList {}
class CListIterator {}
class CMap {}
class CMapIterator {}

// Interfaces (vazias para fins de stub)
interface IApplicationComponent {}
interface ICache {}
interface ICacheDependency {}
interface IStatePersister {}
interface IFilter {}
interface IAction {}
interface IWebServiceProvider {}
interface IViewRenderer {}
interface IUserIdentity {}
interface IWebUser {}
interface IAuthManager {}
interface IBehavior {}
interface IWidgetFactory {}
interface IDataProvider {}
