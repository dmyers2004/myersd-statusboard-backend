<?php
/**
* DMyers Super Simple MVC
*
* @package    view for SSMVC
* @language   PHP
* @author     Don Myers
* @copyright  Copyright (c) 2011
* @license    Released under the MIT License.
*
* @Singleton
*
*/
namespace libraries;

class view {
	public $data = array(); /* view data */
	public $folder = '';

	public function __construct(&$c) {
		$c['view'] = $this; /* assign a copy of me to the application */
		$this->folder = $c['config']['dispatch']['folders']['view'];
	}

	public function load($file,$return=true) {
		$capture = '';

		$file = $this->folder.$file.'.php';

		if (is_file($file)) {
			$capture = $this->_capture($file,$this->data);
		}

		if ($return === false) {
			echo $capture;
		}

		if ($return === true) {
			return $capture;
		}

		if (is_string($return)) {
			$this->data[$name] = $capture;
		}

		return $this;
	}

	public function set($name,$value=null,$where=null) {
		if (is_array($name)) {
			foreach ($name as $key => $value) {
				$this->set($key,$value);
			}
			return $this;
		}

		if (is_string($value)) {
			$where = ($where) ? $where : '>';
		} else {
			$where = '#'; /* overwrite (default) */
		}

		switch ($where) {
			case '>': /* Apend */
				$this->data[$name] = $this->data[$name].$value;
			break;
			case '<': /* Prepend */
				$this->data[$name] = $value.$this->data[$name];
			break;
			default: /* Overwrite */
				$this->data[$name] = $value;
		}

		return $this;
	}

	public function get($name=null) {
		if ($name == null) {
			return $this->data;
		}

		return $this->data[$name];
	}

	public function json($value=null,$options=0) {
		$value = ($value == null) ? $this->data : $value;
		
		header('Cache-Control: no-cache, must-revalidate');
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		header('Content-Type: application/json; charset=utf=8');
		
		return json_encode($value,$options);
	}

	private function _capture($_mvc_file,$_mvc_data) {
		extract((array) $_mvc_data);
		ob_start();
		include($_mvc_file);
		return ob_get_clean();
	}

}