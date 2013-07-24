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

class chartController extends basePublicController {
	public function __construct(&$c) {
		parent::__construct($c);

		new \libraries\view($this->c);
	}

	public function sectionAjaxAction($id) {
		
		for ($i = 1;$i<=7;$i++) {
			$a[] = mt_rand(1, 100);
			$b[] = mt_rand(1, 100);	
		}

		$data =array('a'=>implode(',',$a),'b'=>implode(',',$b));
		
		return $this->c['view']->set($data)->load('section1');
	}

} /* end controller */