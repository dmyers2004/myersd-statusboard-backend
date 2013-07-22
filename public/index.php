<?php
/* where are we? - this is used a lot so let's define it */
define('PATH', realpath(__DIR__.'/../app').'/');

/* examples
define('PATH', __DIR__); - everything in root folder
define('PATH', realpath(__DIR__.'/..')); - everything in "public" folder and app 1 level down 
*/

/* register the PSR-0-ish autoloader */
spl_autoload_register(function ($classname) {
  preg_match('/^(.+)?([^\\\\]+)$/U', ltrim($classname, "\\"), $match);
  include_once PATH.str_replace("\\", "/", $match[1]).str_replace(["\\", '_'], '/', $match[2]).'.php';
});

/* setup our dependency injection container */
$c = array();

/* load our config & input settings - or testing mockup */
require PATH.'config.php';

$c['config'] = $config;

/* load our "input" - or testing mockup */
$c['input'] = $input;

/* setup our output */
$c['output'] = '';

/* attach our hook system */
$c['hooks'] = new \libraries\hooks($c);

/* create dispatcher and dispatch! */
$c['dispatch'] = new \libraries\dispatch($c);

echo $c['output'];

/* Show Request time & memory usage -- comment/uncomment */
/*
echo '<p>'.(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT']).'ms</p>';
echo '<p>'.(memory_get_peak_usage(true)/1024).'k</p>';
*/