<?php
/**
* DMyers Super Simple MVC
*
* @package    Database for SSMVC
* @language   PHP
* @author     Don Myers
* @copyright  Copyright (c) 2011
* @license    Released under the MIT License.
*/
namespace libraries;

class database {
	static public $c;

	public function __construct(&$c) {
		self::$c = $c;
	}

	public function connect($dsn,$user,$password,$connection='default') {
		/* if the connection isn't there then try to create it */
		if (!isset(self::$c['config']['database'][$connection])) {
			try {
				$handle = new \PDO($dsn , $user, $password);
			} catch (PDOException $e) {
				throw new \Exception($e->getMessage());
			}
			self::$c['config']['database'][$connection] = $handle;
		}

		return self::$c['config']['database'][$connection];
	}

	public function connection($connection='default') {
		$prefix = ($connection == 'default') ? '' : $connection.'.';

		$dsn = self::$c['config']['database']['db.'.$prefix.'dsn'];
		$user = self::$c['config']['database']['db.'.$prefix.'user'];
		$password = self::$c['config']['database']['db.'.$prefix.'password'];

		return $this->connect($dsn,$user,$password,$connection);
	}

	public function _columns($tablename,$connection='default') {
		$connection = $this->connection($connection);

		$statement = $connection->prepare('DESCRIBE '.$tablename);
		$statement->execute();
		$table_fields = $statement->fetchAll(PDO::FETCH_COLUMN);
		echo "\$this->fields = array('".implode("','",$table_fields)."');";
	}

}
