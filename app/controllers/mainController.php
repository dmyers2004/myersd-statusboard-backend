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

		foreach ($this->c['config']['elements'] as $e) {
			$onready .= $this->section($e[0],$e[1],$e[2]);
		}
		return $this->c['view']
			->set('onready',$onready)
			->set('baseurl',$this->c['config']['dispatch']['base url'],'#')
			->load('layout');
	}

	public function sectionAjaxAction($id) {
		return 'Time '.date(DATE_RFC822).'<br>Hi I am Section '.$id.'<br>I refresh ever '.$this->c['config']['elements'][$id-1][1].' seconds';
	}

	private function section($id,$seconds,$url) {
		$y = "/* load it the first time */\ntrigger('$url','$id');\n";
		$x = "/* set the interval */\nvar section".$id."=setInterval(function(){".$y."}, ".$seconds."*1000);\n";

		return $y.$x;
	}

} /* end controller */