<?php
require_once('config/config.php');
require_once('services/queue.php');
require_once('services/validator.php');

class Message {

	public $maxMassageSize;

	public function __construct(){
		$this->maxMassageSize = Config::params('max_size');
	}

	/*
	* Sending message
	*/
	public function send($request, $return=''){

		$validate = Validator::validate($request);
		if ($validate === true) {
			$messages_chunks = $this->filterMessage($request['message']);
			$to_number = $this->filterNumber($request['country_code'], $request['cellphone_number']);
			
			$response = $this->sendMessage($to_number, $messages_chunks);
			if ($response == Config::params('status')->sent) {
				if($return === 1) return $response;
				
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

	/*
	* Adding message on a queue
	*/
	private function sendMessage($to_number, $messagesChunks){

		Queue::message($to_number, $messagesChunks);

		return Config::params('status')->sent;
	}


	/* 
	* Filter message, removing unecessary character
	* Also breaking long messages into chunks
	*/
	private function filterMessage($message=''){
		
		$message = $this->removeInvalidCharacters($message);
		$messageLength = strlen($message);
		$message_parts = [];

		if ($messageLength > $this->maxMassageSize){
			$totalChunks = ceil($messageLength/$this->maxMassageSize);
			$chunks = ( $totalChunks > 0 ) ? $totalChunks : 1;
			$message_parts = $this->breakMessages($message, $chunks);

		} else {
			$message_parts[] = $message;
		}

		return $message_parts;
	}

	/*
	* Filtering cell phone number
	*/
	private function filterNumber($countryCode, $cellphoneNumber){
		return $countryCode . $cellphoneNumber;
	}

	/*
	* Breaking long messages into chunks
	*/
	private function breakMessages($message, $chunkSize=1){
		$message_parts = [];

		for($x=0; $x<$chunkSize; $x++) {
			$start = ($x*$this->maxMassageSize);
			$end = $this->maxMassageSize;
			$message_parts[] = substr($message, $start, $end);
		}

		return $message_parts;
	}

	/*
	* Removing unnecessary html tags
	*/
	private function removeInvalidCharacters($message=''){
		return  strip_tags($message);
	}

}
