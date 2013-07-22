<?php
/**
	* DMyers Super Simple MVC
	*
	* @package    Bootstrap File
	* @language   PHP
	* @author     Don Myers
	* @copyright  Copyright (c) 2011
	* @license    Released under the MIT License.
	*
	*/
namespace controllers;

class blueController extends basePublicController {

	public function __construct(&$app) {
		parent::__construct($app);
	}
	
	public function indexAction() {
		return '<h1>BlueIndex</h1><pre>'.print_r($this,true);
	}
	
	public function shoesAction() {
		return '<h1>BlueShoes</h1><pre>'.print_r($this,true);
	}
	
} /* end controller */
