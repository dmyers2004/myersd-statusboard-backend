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
		$onready .= $this->section(2,7,'/main/section/1');
		$onready .= $this->section(3,9,'/main/section/1');
		$onready .= $this->section(4,11,'/main/section/1');
		$onready .= $this->section(5,13,'/main/section/1');
		$onready .= $this->section(6,18,'/main/section/1');
						
		return $this->c['view']
			->set('onready',$onready)
			->set('baseurl',$this->c['config']['dispatch']['base url'],'#')
			->load('layout');
	}
	
	public function sectionAjaxAction($id) {
		return 'Time '.date(DATE_RFC822).' Section '.$id;
	}

	private function section($id,$seconds,$url) {
		$y  = 'jQuery.get(\''.$url.'\',function(d,t,j){jQuery(\'#section'.$id.'\').html(d);},\'html\');';
		$x = $y . 'var section'.$id.'=setInterval(function(){'.$y.'}, '.$seconds.'*1000);';
		return $x;
	}
	
} /* end controller */
