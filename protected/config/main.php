<?php return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Socialii',

	'preload' => array(),
	'import'=>array(
		'application.models.*',
	),

	'components'=>array(
		//CREATE USER 'ch5_socialii'@'localhost' IDENTIFIED BY 'ch5_socialii';
		//CREATE DATABASE IF NOT EXISTS  `ch5_socialii` ;
		//GRANT ALL PRIVILEGES ON  `ch5\_socialii` . * TO  'ch5_socialii'@'localhost';
		'db' => array(
		  'class' => 'CDbConnection',
		  'connectionString' => 'mysql:host=127.0.0.1;dbname=ch5_socialii',
		  'emulatePrepare' => true,
		  'username' => 'ch5_socialii',
		  'password' => 'ch5_socialii',
		  'charset' => 'utf8',
		  'schemaCachingDuration' => '3600',
		  'enableProfiling' => true,
		),

		'errorHandler'=>array(
		  'errorAction'=>'site/error',
    	),

		'urlManager'=>array(
			'urlFormat'=>'path',
			'showScriptName'=>false,
			'rules'=>array(
				'<controller:\w+>/<action:\w+>/<id:\w+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),

		'log' => array(
		   'class' => 'CLogRouter',
		   'routes' => array(
			 array(
			   'class' => 'CWebLogRoute',
			   'levels' => 'error, warning, trace, info',
			   'enabled' => false
			 )
		   )
		),

		'cache' => array(
		  'class' => 'CFileCache',
		)
	),

	'params' => array(
		'includes' => require __DIR__ . '/params.php'
	)
);
