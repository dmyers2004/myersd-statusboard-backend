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
		'#^/AjaxGet/section/(.*)$#i' => '/Ajax/main/section/$1',
		'#^/AjaxGet/(.*)/(.*)$#i' => '/Ajax/$1/$2',
		'#^/Get/(.*)/(.*)$#i' => '//$1/$2'
	)

);

/* database config */
$config['database'] = array(
	'db.dsn' => 'sqlite:'.$config['dispatch']['folders']['sqlite'] .'messaging.sqlite3',
	'db.user' => null,
	'db.password' => null,
);

/* get this from the DB in the future */
$config['elements'] = array(
	array(1,4,'/section/1'),
	array(2,5,'/section/2'),
	array(3,6,'/section/3'),
	array(4,7,'/section/4'),
	array(5,8,'/section/5'),
	array(6,9,'/section/6'),
	array(7,1,'/section/7'),
	array(8,2,'/section/8'),
	array(9,3,'/section/9'),
	array(10,4,'/section/10'),
	array(11,5,'/section/11'),
	array(12,6,'/section/12'),
	array(13,7,'/section/13'),
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
