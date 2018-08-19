<?php
require_once('config/config.php');
require_once('classes/queue.php');

class Message {

	public function __construct(){

		$this->maxMassageSize = Config::params('max_size');

	}

	/*
	* Sending message
	*/
	public function send($request){
		$messages = $this->filterMessage($request['message']);
		$response = $this->sendMessage($messages);
		if ($response==Config::params('status')->sent) {
			//header('Location: index.php?status='.Config::params('status')->sent);
		}
	}

	/*
	* Get either message successed of failed per provided status
	*/
	public function getMessage($status='') {
		$message = '';

		if ($status==Config::params('status')->sent) $message = "Message has been sent successfully";
		
		return $message;
	}




	private function filterMessage($message=''){
		
		$message = $this->removeInvalidCharacters($message);
		$messageLength = strlen($message);
		$message_parts = [];

		if ($messageLength > $this->maxMassageSize){
			$totalChunks = floor($messageLength/$this->maxMassageSize);
			$chunks = ( $totalChunks > 0) ? $totalChunks : 1 ; 

			for($x=0; $x<$chunks; $x++) {
				$start = ($x*150);
				$end = $this->maxMassageSize;
				$message_parts[] = substr($message, $start, $end);
			}

		} else {
			$message_parts[] = $message;
		}

		return $message_parts;
	}


	private function sendMessage($messages){
		Queue::message();
		return Config::params('status')->sent;
	}


	private function removeInvalidCharacters($message=''){
		return  strip_tags($message);
	}

}
