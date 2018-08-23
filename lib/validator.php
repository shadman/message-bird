<?php
require_once('config/config.php');

class Validator {


	/*
	* Validate client request, before sending any message
	*/
	public function validate($request){

		$message = '';

		$maxMessageLength = Config::params('max_full_message_length');
		$minMessageLength = Config::params('min_full_message_length');
		if ($request['message'] == NULL || strlen($request['message']) < $minMessageLength || strlen($request['message']) > $maxMessageLength ) 
			$message = "Invalid message found. Message length should be in between $minMessageLength and $maxMessageLength";
		else if ( strlen($request['country_code']) < Config::params('min_country_code')  ||
			!is_numeric($request['country_code']) ) $message = "Invalid country code found";
		else if ( strlen($request['cellphone_number']) < Config::params('min_cellphone_number')  || 
			!is_numeric($request['cellphone_number']) ) $message = "Invalid cellphone number found";
		

		if ($message === '') {

			// Check daily limit for specific IP
			$maxMessageIPDailyLimit = Config::params('ip_daily_message_limit');
			$countOfMessage = self::getCountOfMessages();
			if ($countOfMessage >= $maxMessageIPDailyLimit ) 
				return "You have exceeded your daily limit, as $maxMessageIPDailyLimit messages per day are allowed.";

			return true;
		}

		return $message;
	}



	/* 
	Private 
	*/

	/*
	* Get a count of messages for specific IP
	*/
	private function getCountOfMessages(){
		
		$ipAddress = $_SERVER['REMOTE_ADDR'];
		$database = new Database;
		$query = $database->getCount('message_queues', 
									 " ip_address='$ipAddress' AND DATE(`created_at`) = CURDATE() ");
		$exec = $database->query($query);
		if ($exec) {
			$row = $database->fetchArray($exec);
			return $row['count'];
		}

		return 0;
	}

}