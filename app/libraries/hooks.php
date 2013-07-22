<?php
/**
* DMyers Super Simple MVC
*
* @package    Hooks for SSMVC
* @language   PHP
* @author     Don Myers
* @copyright  Copyright (c) 2011
* @license    Released under the MIT License.
*
* Hooks:
* startup
* preRouter
* preController
* preMethod
* preOutput
*/
namespace libraries;

class hooks {

	/* called after turn off all errors (default) and register the autoloader */
	public function startup(&$c) {
		/* turn them back on (this could be based on $app->config['app']['run code'] or something else */
		error_reporting(E_ALL & ~E_NOTICE);

		/* Default timezone of server */
		date_default_timezone_set('UTC');

		/* setup error handler */
		new \libraries\errorhandler;

		/* Start Session */
		if (!headers_sent()) {
			session_save_path($app->config['app']['folders']['session']);
			session_name('s'.md5($app->config['app']['base url']));
			session_start();
		}
	}

	/* called before the controller and method and request type is actually used */
	public function preRouter(&$c) {
	}

	/* called before the controller is instantiated */
	public function preController(&$c) {
	}
	
	/* called before the method on the controller is called */
	public function preMethod(&$c) {
	}

	/* if the contoller has returned anything it will be in $app->output */
	public function preOutput(&$c) {
	}
	
	/* you can add additional hooks here */

} /* end hooks */
