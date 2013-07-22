<?php
/**
* DMyers Super Simple MVC
*
* @package    orm for SSMVC
* @language   PHP
* @author     Don Myers
* @copyright  Copyright (c) 2011
* @license    Released under the MIT License.
*/
namespace libraries;

class orm extends database {

	public $records = array();
	public $count = 0;
	public $perpage = 10;

	public $debug = FALSE;
	public $connection;
	public $pkname;
	public $tablename;
	public $fields = array(); // for holding all object property variables
	public $data = array();

	public function __construct() {
		/* no need to call database parent it should already be setup */
		$this->clear();
	}

	public function clear() {
		$this->records = array();
		$this->count = 0;
		foreach ($this->fields as $field) {
			$this->data[$field] = '';
		}
	}

	public function debug($on=TRUE) {
		$this->debug = $on;
	}

	public function perPage($cnt=NULL) {
		if ($cnt) {
			$this->perpage = $cnt;
		}
		return $this->perpage;
	}

	public function records() {
		return $this->records;
	}

	public function count() {
		return $this->count;
	}

	public function __get($key) {
		return $this->data[$key];
	}

	public function __set($key, $val) {
		if (isset($this->data[$key])) {
			$this->data[$key] = $val;
		}
		return $this;
	}

	// Inserts record into database with a new auto-incremented primary key
	// If the primary key is empty, then the PK column should have been set to auto increment
	public function create() {
		$into = $values = '';
		$bindings = array();

		foreach ($this->data as $key => $val) {
			if ($key != $this->pkname) {
				$into .= ',`'.$key.'`';
				$values .= ',:'.$key;
				$bindings[':'.$key] = $val;
			}
		}

		$sql = 'INSERT INTO `'.$this->tablename.'` ('.substr($into,1).') VALUES ('.substr($values,1).')';
		$this->execute($sql,$bindings);

		if ($this->count > 0) {
			$this->data[$this->pkname] = $this->connection->lastInsertId();
		}

		return $this;
	}

	public function update() {
		$set = '';
		$bindings = array();

		foreach ($this->data as $key => $val) {
			if ($key != $this->pkname) {
				$set .= ',`'.$key.'`=:'.$key;
			}
			$bindings[':'.$key] = $val;
		}

		$sql = 'UPDATE `'.$this->tablename.'` SET '.substr($set,1).' WHERE '.$this->buildPkWhere();
		return $this->execute($sql,$bindings);
	}

	public function save() {
		if (!empty($this->data[$this->pkname])) {
			return $this->update();
		} else {
			return $this->create();
		}
	}

	public function delete($pkvalue=NULL) {
		$pkvalue = ($pkvalue) ? $pkvalue : $this->data[$this->pkname];

		$sql = 'DELETE FROM `'.$this->tablename.'` WHERE '.$this->buildPkWhere();

		$bol = $this->execute($sql,array(':'.$this->pkname => $pkvalue));

		$this->clear();

		return $bol;
	}

	public function read($pkvalue=NULL) {
		return $this->readMany(array($this->pkname => $pkvalue),1);
	}

	public function readOne($where='') {
		return $this->readMany($where,1);
	}

	public function paginate($where='',$page,$perpage=NULL) {
		$perpage = ($perpage) ? $perpage : $this->perpage;
		return $this->readMany($where,(($page - 1) * $perpage).','.($start + $perpage));
	}

	public function readMany($where='',$limit=NULL) {
		$cursor = $this->select('*',$where,PDO::FETCH_ASSOC,$limit);

		$class = get_called_class();

		$this->records = array();
		foreach ($cursor as $record) {
			$single = new $class($this->connection);
			foreach ($record as $key => $val) {
				if (in_array($key,$this->fields)) {
					$single->$key = $val;
				}
			}
			$this->records[] = $single;
		}

		$this->data = $this->records[0]->data;

		return $this->records;
	}

	public function readList($columns='',$where='') {
		$cursor = $this->select($columns,$where,PDO::FETCH_NUM);

		$records = array();

		foreach ($cursor as $record) {
			$records[$record[0]] = $record[1];
		}

		return $records;
	}

	public function select($columns='*',$where=NULL,$pdo_fetch_mode=PDO::FETCH_ASSOC,$limit=NULL) {
		$sql = 'SELECT '.$columns.' FROM `'.$this->tablename.'`';

		$where = $this->buildWhere($where);

		if (!empty($where['where'])) {
			$sql .= ' WHERE '.$where['where'];
		}

		if ($limit) {
			$sql .= ' LIMIT '.$limit;
		}

		$statement = $this->execute($sql,$where['bindings']);

		return $statement->fetchAll($pdo_fetch_mode);
	}

	public function mergeBindings(&$statement,&$bindings) {
		if ($bindings && $statement) {

			if (is_scalar($bindings)) {
				$bindings = array($bindings);
			}

			foreach ($bindings as $key => $value) {
				$bol = $statement->bindValue($key,$value);
			}
		}
	}

	public function execute($sql,$bindings=NULL) {		
		$statement = $this->connection->prepare($sql);

		if ($this->debug) {
			echo '<pre>SQL: '.$sql.chr(10).'Bindings'.substr(print_r($bindings,TRUE),5).'</pre>';
		}

		if ($bindings) {
			$this->mergeBindings($statement,$bindings);
		}

		$statement->execute();
		$this->count = $statement->rowCount();

		if ($statement->errorCode() > 0) {
			trigger_error('SQL Error<br>'.$sql.'<br><pre>'.print_r($statement->errorInfo,TRUE).'</pre>');
		}

		return $statement;
	}

	public function buildPkWhere() {
		return '`'.$this->pkname.'`= :'.$this->pkname;
	}

	public function buildWhere($clause=NULL) {
		$where = $bindings = array();

		if (is_array($clause)) {
			foreach ((array) $clause as $key => $val) {
				$op = '=';
				if (is_array($val)) {
					$op = $val[0];
					$val = $val[1];
				}
				$where[] = $key.' '.$op.' :'.$key;
				$bindings[':'.$key] = $val;
			}
		}

		return array('where'=>implode(', ',$where),'bindings'=>$bindings);
	}

	//returns TRUE if primary key is a positive integer
	//if checkdb is set to TRUE, this public function will return TRUE if there exists such a record in the database
	public function exists($pkvalue=NULL) {
		/* if no pk value sent in then just test the current record */
		if (!$pkvalue) {
			return !empty($this->data[$this->pkname]);
		}

		/* swap count to not overwrite it */
		$count = $this->count;
		$this->select('1',array($this->pkname=>$pkvalue));
		$reply = $this->count;
		$this->count = $count;

		return ($reply != 0);
	}

	public function merge($arr=NULL) {
		$ary = ($ary) ? $ary : $_POST;
		if (is_array($ary)) {
			foreach ($ary as $key => $val) {
				if (in_array($key,$this->fields)) {
					$this->data[$key] = $val;
				}
			}
		}

		return $this;
	}

} // close class
