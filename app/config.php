<?php

/* setup "assume nothing" config/injection and start the party! */
$config['dispatch'] = array(
 	'run code' => getenv('RUNCODE'),
 	'handler' => php_sapi_name(),

	'default controller' => 'main',
	'default method' => 'index',
	'controller suffix' => 'Controller',
	'method suffix' => 'Action',
	'ajax prefix' => 'Ajax',

	'folder' => PATH,
	
	'folders' => array(
		'view' => PATH.'views/',
		'logs' => PATH.'var/logs/',
		'cache' => PATH.'var/cache/',
		'session' => PATH.'var/sessions/',
		'sqlite' => PATH.'var/sqlite/'
	),

	'routes' => array(
		'#^helloController/(.*)GetAction(.*)$#i' => 'mainController/helloAction/$1$2',
		'#^(.*)/(.*)GetAction$#i' => '$1/$2Action',
		'#^(.*)Controller/(.*)GetAction(.*)$#i' => '$1Controller/$2Action$3'
	)

);

/* database config */
$config['database'] = array(
	'db.dsn' => 'sqlite:'.$config['dispatch']['folders']['sqlite'] .'messaging.sqlite3',
	'db.user' => null,
	'db.password' => null,

	'db.mysql.dsn' => 'mysql:host=localhost;dbname=pi',
	'db.mysql.user' => 'root',
	'db.mysql.password' => 'root'
);

/* PHP HTTP Put handler */
$_PUT = array();
\parse_str(file_get_contents('php://input'), $_PUT);

/* injection! baby! */
$input = array(
	'server' => $_SERVER,
	'get' => $_GET,
	'post' => $_POST,
	'files' => $_FILES,
	'cookies' => $_COOKIE,
	'env' => $_ENV,
	'session' => $_SESSION,
	'put' => $_PUT
);


/*
	'#^hello/(.*)$#i' => 'main/hello/$1',
	'#^unit/test$#i' => 'main/unit_test',
	'#^user/(.*)$#i' => 'main/user/$1',
	'#^app/test(.*)#i' => 'main/index$1',
	'#^app(.*)$#i' => 'main/app$1',
	'#^rest/(.*)$#i' => 'rest/index/$1',
*/