<?php
require_once('config/config.php');

class Database {

	public function __construct(){
	
		$this->db_name = Config::params('db_name');
		$this->db_host = Config::params('db_host');
		$this->db_username = Config::params('db_username');
		$this->db_password = Config::params('db_password');
	
	}

	public function connection(){
		
	}

}