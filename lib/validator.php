<?php

class Validator {

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
			
		if ($message === '') return true;
		
		return $message;
	}

}