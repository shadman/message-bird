<?php
require_once('config/config.php');
require_once('lib/database.php');

class SendMessages {

	public function send(){
		
		$database = new Database();
		$data = array(
			'cellphone' => $to,
			'message' => json_encode($messages),
			'ip_address' => $_SERVER['REMOTE_ADDR']
		);

		$query = $database->selectOne('message_queues', array('id','is_executed','executed_at','message','cellphone'), 
									  ' is_executed=0 ');
		$exec = $database->query($query);
		if ($exec) {
			$row = $database->fetchArray($exec);

			// Transaction Started
			//{

				// Preparing message requests
				$messages = json_decode($row['message'], true);
				foreach ($messages as $messagePart){
					$request = array(
						'message' => $row['message'],
						'cellphone' => $row['cellphone'],
					);	

					// Sending Message

				}


			$id = $row['id'];
			// Update message status


			//}
			// Transaction End

			return json_encode(array('success'=>true));
		}

	}

}