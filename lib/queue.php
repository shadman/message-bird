<?php
require_once('config/config.php');
require_once('lib/database.php');

class Queue {

	public function message($to, $messages){
		
		$database = new Database();
		$data = array(
			'cellphone' => $to,
			'message' => json_encode($messages),
			'ip_address' => $_SERVER['REMOTE_ADDR']
		);

		$query = $database->insert('message_queues', $data);
		$database->query($query);
	}

}