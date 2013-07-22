<?php
/**
	* DMyers Super Simple MVC
	*
	* @package    Example Model File
	* @language   PHP
	* @author     Don Myers
	* @copyright  Copyright (c) 2011
	* @license    Released under the MIT License.
	*
	*/
namespace models;

class mpeople extends \libraries\orm {

	public function __construct() {
		$this->tablename = 'people';
		$this->pkname = 'id'; //Name of auto-incremented Primary Key
		$this->fields = array('id','hash','keyword_id');
		$this->connection = $this->connection(); // database connection

		parent::__construct();
	}

}
