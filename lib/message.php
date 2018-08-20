<?php
require_once('config/config.php');
require_once('lib/queue.php');
require_once('lib/validator.php');

class Message {

	public $maxMassageSize;

	public function __construct(){

		$this->maxMassageSize = Config::params('max_size');

	}

	/*
	* Sending message
	*/
	public function send($request){
		$validate = Validator::validate($request);
		if ($validate === true) {
			$messages_chunks = $this->filterMessage($request['message']);
			$to_number = $this->filterNumber($request['country_code'], $request['cellphone_number']);
			
			$response = $this->sendMessage($to_number, $messages_chunks);
			if ($response == Config::params('status')->sent) {
				header('Location: index.php?status='.Config::params('status')->sent);
			}

		} else {
			$response = $validate;
		}
		return $response;
	}

	/*
	* Get either message successed of failed per provided status
	*/
	public function getMessage($status='') {
		$message = '';

		if ($status == Config::params('status')->sent) $message = "Message has been sent successfully";
		else if ($status == Config::params('status')->invalid_request) $message = "Invalid request found";
		return $message;
	}



	/*
	* Private methods
	*/
	private function sendMessage($to_number, $messagesChunks){
		Queue::message($to_number, $messagesChunks);
		return Config::params('status')->sent;
	}


	private function filterMessage($message=''){
		
		$message = $this->removeInvalidCharacters($message);
		$messageLength = strlen($message);
		$message_parts = [];

		if ($messageLength > $this->maxMassageSize){
			$totalChunks = floor($messageLength/$this->maxMassageSize);
			$chunks = ( $totalChunks > 0) ? $totalChunks : 1 ; 
			$message_parts[] = $this->breakMessages($message, $chunks);

		} else {
			$message_parts[] = $message;
		}

		return $message_parts;
	}


	private function filterNumber($countryCode, $cellphoneNumber){
		return $countryCode . $cellphoneNumber;
	}


	private function breakMessages($message, $chunkSize=1){
		$message_parts = [];

		for($x=0; $x<$chunkSize; $x++) {
			$start = ($x*150);
			$end = $this->maxMassageSize;
			$message_parts[] = substr($message, $start, $end);
		}
		return $message_parts;
	}


	private function removeInvalidCharacters($message=''){
		return  strip_tags($message);
	}

}
