<?php
require_once('config/config.php');
require_once('services/database.php');

class Queue {

	/* 
	* Queue to hold every request of users from client
	*/
	public function message($to, $messages){
		
		$database = new Database();
		$data = array(
			'cellphone' => $to,
			'message' => json_encode($messages),
			'ip_address' => (isset($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : ''
		);

		$query = $database->insert('message_queues', $data);
		$database->query($query);
	}

}