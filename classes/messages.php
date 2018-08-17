<?php
require_once('config/config.php');

print_r($config);

class Message {

	public function __construct(){
		$this->maxMassageSize = 150;
	}

	public function send($request){
		print_r($request);

		$messages = $this->filterMessage($request['message']);
		$response = $this->sendMessage($messages);
		if ($response=='sent') {
			header('Location: index.php?status=sent');
		}
	}

	public function getMessage($status='') {
		$message = '';

		if ($status=='sent') $message = "Message has been sent successfully";
		
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
		return 'sent';
	}

	private function removeInvalidCharacters($message=''){
		return  strip_tags($message);
	}
}
