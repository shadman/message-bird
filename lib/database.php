<?php
require_once('config/config.php');

class Database {


	public $database, $host, $user, $password, $connect;

	public function __construct(){
		$this->database = Config::params('db_name');
		$this->host = Config::params('db_host');
		$this->user = Config::params('db_username');
		$this->password = Config::params('db_password');
		$this->connect();
	}

	/*
	* Connect Database
	*/
    public function connect(){
		$this->connect = mysqli_connect($this->host, $this->user, $this->password, $this->database);
		if (!$this->connect) {
			die("Could not connect: " . mysqli_connect_error() . "\n");
		}
    }

	/*
	* Preparing Insert Query
	*/
	public function insert($tableName, $valuesArray){
		$fields = implode(",", array_keys($valuesArray));
		$valuesArray = $this->checkingValues($valuesArray);
		$values = "'".implode("','", $valuesArray)."'";

		return "INSERT into $tableName ($fields) VALUES ($values) ";
	}

	/*
	* Preparing Select Single Record Query
	*/
	public function selectOne($tableName, $fieldsArray='*', $where='', 
							  $orderBy='id', $order='ASC'){
		$fields = implode(',', $fieldsArray);

		if ($where) $where = " WHERE $where ";
		return "SELECT $fields FROM $tableName $where ORDER BY $orderBy $order LIMIT 1";
		
	}

	/*
	* Preparing Select All Query
	*/
	public function selectAll($tableName, $fieldsArray='*', $where='', 
							  $limit=100, $orderBy='id', $order='ASC'){
		$fields = implode('*', $fieldsArray);

		if ($where) $where = " WHERE $where ";
		return "SELECT $fields FROM $tableName $where LIMIT $limit";
		
	}

	/*
	* Preparing Get A Count Query
	*/
	public function getCount($tableName, $where=''){

		if ($where) $where = " WHERE $where ";
		return "SELECT count(*) as count FROM $tableName $where";
	}

	/*
	* Executing Query
	*/
	public function query($query){
		return mysqli_query($this->connect, $query);
	}

	/*
	* Fetch Record
	*/
	public function fetchArray($exec){
		return mysqli_fetch_array($exec);
	}


	/* Private Methods */

	/*
	* Escaping Stings From All Values, Before Insertion
	*/
	private function checkingValues($valuesArray){
		foreach ($valuesArray as $value) {
			$filteredValues[] = $this->escapeString($value);
		}
		return $filteredValues;
	}	

	/*
	* Escaping String
	*/
	private function escapeString($string=NULL){
	  return mysqli_real_escape_string($this->connect, $string);
	}

}