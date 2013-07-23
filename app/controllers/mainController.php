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
	public function __construct(&$c) {
		parent::__construct($c);

		new \libraries\view($this->c);
	}
	
	public function indexAction() {
		
		$onready = '';
		$onready .= $this->section(1,5,'/main/section/1');
		$onready .= $this->section(2,7,'/main/section/2');
		$onready .= $this->section(3,9,'/main/section/3');
		$onready .= $this->section(4,11,'/main/section/4');
		$onready .= $this->section(5,13,'/main/section/5');
		$onready .= $this->section(6,18,'/main/section/6');
						
		return $this->c['view']
			->set('onready',$onready)
			->set('baseurl',$this->c['config']['dispatch']['base url'],'#')
			->load('layout');
	}
	
	public function sectionAjaxAction($id) {
		return 'Time '.date(DATE_RFC822).' Section '.$id;
	}

	private function section($id,$seconds,$url) {
		$y = "/* load it the first time */\ntrigger('$url','$id');\n";
		$x = "/* set the interval */\nvar section".$id."=setInterval(function(){".$y."}, ".$seconds."*1000);\n";
		
		return $y.$x;
	}
	
} /* end controller */
