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

class mainController extends basePublicController {

	/*
	pass injected $app to parent to setup
	you could handle it here but by extending
	basePublicController I only need to write the logic once
	another base class could be baseAdminController or jsonPublicContoller
	which could also extend basePublicController for example
	*/
	public function __construct(&$c) {
		parent::__construct($c);
	}
	
	public function indexAction() {
		return '<pre>mainController Loaded indexAction Run '.print_r($this->c,true);
	}
	
	public function helloAction($name) {
		return 'Hello '.$name.'<pre>'.print_r($this->c,true);
	}
	
	public function viewAction() {
		/* you could create the view object in basePublicController construct or within a hook */
		new \libraries\view($this->c);
		
		$this->c['view']->set('baseurl',$this->c['config']['dispatch']['base url'],'#');

		return $this->c['view']
			->set('body','<h2>This is the body</h2>')
			->load('layout');
	}
	
	public function dbAction() {
		/* you could do this in basePublicController construct or with a hook */
		new \libraries\database($this->c);
		
		echo '<pre>';

		$mPeople = new \models\mpeople;

		$mPeople->keyword_id = mt_rand(1, 9999);
		$mPeople->hash = md5($mPeople->keyword_id);
		$mPeople->create();

		print_r($mPeople);

		var_dump($mPeople->count());
	}
	
	public function jsonAction() {
		/* you could do this in basePublicController construct or with a hook */
		new \libraries\view($this->c);
		
		return $this->c['view']
			->set(array('name'=>'Don','age'=>42))
			->json($data);
	}
	
} /* end controller */
