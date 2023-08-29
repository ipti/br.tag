<?php

// unomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
// CWebApplication propeties can be configured here.


$log_config = array(
    'class' => 'CLogRouter',
    'routes' => array(
        array(
            'class' => 'CFileLogRoute',
            'levels' => 'error, warning',
        ),
    ),
);

if(YII_DEBUG){
    array_push($log_config['routes'], array(
        'class'=>'CWebLogRoute',
      )
    );
}



return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'TAG',
    'theme' => 'default',
    'sourceLanguage' => 'pt-br',
    'language' => 'pt_br',
    // preloading 'log' component
    'preload' => array('log'),
    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.controllers.*',
        'application.components.*',
        'application.modules.wizard.models.*',
        'application.modules.tag.models.*',
        'application.modules.tag.components.interfaces.*',
        'application.modules.tag.components.adapter.*',
        'application.modules.calendar.models.*',
        'application.modules.curricularmatrix.models.*',
        'application.modules.quiz.models.*',
        'application.modules.sedsp.datasources.sed.*',
        'application.modules.sagres.soap.src.sagresEdu.*',
        'application.components.utils.TagUtils',
        'ext.bncc-import.BNCCImport'
    ),
    'modules' => array(
        // uncomment the following to enable the Gii tool
        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => 'p@s4tag',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters' => array('*'),
            'generatorPaths' => array(

            ),
        ),
        'wizard',
        'lunch',
        'resultsmanagement',
        'schoolreport',
        'calendar',
        'timesheet',
        'curricularmatrix',
        'quiz',
        'sagres',
        'tag',
        'professional',
        'sedsp',
        'classdiary',
        'curricularcomponents'
    ),
    // application components
    'components' => array(
        'utils' => array(
            'class' => 'application.components.utils.TagUtils'
        ),
        'assetManager' => array(
            'forceCopy' => YII_DEBUG
        ),
        'user' => array(
            // enable cookie-based authentication
            'allowAutoLogin' => true,
        ),
        'cache'=>array( 
            'class'=>'system.caching.CDbCache'
        ),
        // uncomment the following to enable URLs in path-format
        'urlManager' => array(
            'urlFormat' => 'get',
            'showScriptName' => false,
            'caseSensitive' => false,
            'rules' => array(
                'matriz-curricular/'                        => 'curricularmatrix/',
                'matriz-curricular/<action:\w+>'            => 'curricularmatrix/curricularmatrix/<action>',
                'matriz-curricular/<action:\w+>/<id:\d+>'   => 'curricularmatrix/curricularmatrix/<action>',

                'quadro-de-horario/'                        => 'timesheet/',
                'quadro-de-horario/<action:\w+>'            => 'timesheet/timesheet/<action>',
                'quadro-de-horario/<action:\w+>/<id:\d+>'   => 'timesheet/timesheet/<action>',

                'calendario/'                               => 'calendar/',
                'calendario/<action:\w+>'                   => 'calendar/default/<action>',
                'calendario/<action:\w+>/<id:\d+>'          => 'calendar/default/<action>',

                'merenda-escolar/'                          => 'lunch/',
                'merenda-escolar/estoque/'                  => 'lunch/stock/',
                'merenda-escolar/estoque/<action:\w+>'      => 'lunch/stock/<action>',
                'merenda-escolar/menu'                      => 'lunch/lunch/',
                'merenda-escolar/menu/<action:\w+>'         => 'lunch/lunch/<action>',
                'merenda-escolar/menu/<action:\w+>/<id:\d+>'=> 'lunch/lunch/<action>',

                'boletim-escolar/'                          => 'schoolreport/',
                'boletim-escolar/'                          => 'schoolreport/default/select',
                'boletim-escolar/notas/<eid:\d+>'           => 'schoolreport/default/grades',
                'boletim-escolar/frequencia/<eid:\d+>'      => 'schoolreport/default/frequency',
                'boletim-escolar/<action:\w+>'              => 'schoolreport/default/<action>',
                'boletim-escolar/<action:\w+>/<eid:\d+>'    => 'schoolreport/default/<action>',

                'questionario/'                               => 'quiz/',
                'questionario/<action:\w+>'                   => 'quiz/default/<action>',
                'questionario/<action:\w+>/<id:\d+>'          => 'quiz/default/<action>',

                '<controller:\w+>/<id:\d+>'                 => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>'    => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>'             => '<controller>/<action>',

                'gestao-resultados/'                      		=> 'resultsmanagement/',
                'gestao-resultados/escola'                      => 'resultsmanagement/managementschool/',

                'gestao-resultados/escola/<action:\w+>'         => 'resultsmanagement/managementschool/<action>',
                'gestao-resultados/escola/<action:\w+>/<sid:\d+>'=> 'resultsmanagement/managementschool/<action>',

            ),
        ),
        // uncomment the following to use a MySQL database
        'db2' => array(
            'connectionString' => 'mysql:host=mariadb-s6vhx-mariadb.mariadb-s6vhx.svc.cluster.local;dbname=com.escola10',
            'emulatePrepare' => true,
            'username' => 'admin',
            'password' => '123456',
            'charset' => 'utf8',
            'class'   => 'CDbConnection'
        ),
        'db' => unserialize(DBCONFIG),
        'authManager' => array(
            'class' => 'CDbAuthManager',
            'connectionID' => 'db',
            'itemTable' => 'auth_item',
            'assignmentTable' => 'auth_assignment',
            'itemChildTable' => 'auth_item_child',
        ),   
        'errorHandler' => array(
            // use 'site/error' action to display errors
            'errorAction' => 'site/error',
        ),
        'log' => $log_config
    ),
    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => array(
        // this is used in contact page
        'adminEmail' => 'webmaster@tag.lo',
    ),
);
