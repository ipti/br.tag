<?php
//@done S1 - 08 - 28 - ACL Controle de permissões e login através do banco de dados, criação de usuários para os secretários na escola e perfil.
//@done S1 - 09 - Criar Tabela de Usuário
//@done S1 - 09 - Criar Tabela NtoN Usuário Escola
//@done S1 - 09 - Criar Models
//@done S1 - 09 - Criar formulário de seleção de Escola
//
//@done S1 - 08 - 29 - ACL O Yii já possui modulo pronto para isso é somente configurar e inseri as permissões no banco, NÃO FAÇAM NA MÃO
//@done S1 - 09 - Criar o modelo ACL
//@done S1 - 08 - Configurar o modelo ACL - http://www.yiiframework.com/doc/guide/1.1/pt_br/topics.auth#sec-4
//
// @done S1 - 31 - Lembrar de associar o usuário a escola para fazer as filtragem necessárias nas telas.
// @todo S2 - 32 - O Cadastro deve ser feito de forma básica, só contendo o nome e dados de acesso.
// @todo S2 - Popar a interrogação durante um intervalo de tempo ao falhar na regra do javascript
// @todo S2 - 33 - Criar um sistema de frequencia como no tag antigo lembrando de associar esta frequencia ao aluno e a turma, inicialmente de forma básica.
// @todo S2 - 34 - A frequencia pode ser feita utilizando como base o diario e o que discutimos anteriormente lembrando da necessidade do BOLSA FAMILIA
// @done S1 - 08 - Filtrar a listagem das turmas por escola da sessão
// @done S1 - 08 - Filtrar a listagem das alunos por escola da sessão
// @done S1 - 08 - Filtrar a listagem das matrículas por escola da sessão
// @done S1 - 08 - Filtrar a listagem das professores por escola da sessão
// @done S1 - Adicionar os inputs nos filtros necessarios
// @done S1 - Inserir o prompt dos campos de instrutor
// @done S1 - AJEITAR O INSTRUCTOR
// @todo S1 - preencher os tooltip helpers da tela de escola
// @done S1 - preencher os tooltip helpers da tela de turma
// @done S1 - preencher os tooltip helpers da tela de matrícula
// @done S1 - preencher os tooltip helpers da tela de aluno
// @done S1 - preencher os tooltip helpers da tela de professor
// 
// 
// @done S1 - 04 - Retirar os links para view do breadcrumb e remover o "atualizar" do final
// 
// @done S1 - ajeitar o filtro do enrollment np index do enrollment
// @done S1 - Tabelas redimensionando, colocar para não redimensionar.
// @done S1 - Ordenar todas as listas dos dados
// @done S1 - remover botão de atualizar 
// @done S1 - Corrigir problema de mudança de aba de forma manual do school
// @done S1 - Corrigir problema de mudança de aba de forma manual do student
// @done S1 - Corrigir problema de mudança de aba de forma manual do instructor
// @done S1 - Corrigir problema de mudança de aba de forma manual do classroom
// @done S1 - Corrigir permissões de acesso às telas
// @done S1 - Atualização dos campos apartir da escola.
// verificar se o criteria do index.php esta funcionando 
// 
// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication propeties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'TAG',
        'theme'=>'default',
        'sourceLanguage'=>'pt-br',
        'language' => 'pt_br',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'p@s4ipti',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
	),

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
		// uncomment the following to enable URLs in path-format
		/*
		'urlManager'=>array(
			'urlFormat'=>'path',
			'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		'db'=>array(
			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
		),
                 */
		// uncomment the following to use a MySQL database
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=TAG_SGE',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => 'p@s4ipti',
			'charset' => 'utf8',
		),
                'authManager'=>array(
                    'class'=>'CDbAuthManager',
                    'connectionID'=>'db',
                ),
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@tag.lo',
	),
);
