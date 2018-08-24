<?php
require_once('services/database.php');
require_once('config/config.php');

require_once(__DIR__ . '/../vendors/autoload.php');

class SendMessages {

	/*
	* Executing Sending Messages Job
	*/
	public function send(){
		
		$database = new Database();
		$data = array(
			'cellphone' => $to,
			'message' => json_encode($messages),
			'ip_address' => $_SERVER['REMOTE_ADDR']
		);

		$query = $database->selectOne('message_queues', array('id',
									  'is_executed','executed_at','message','cellphone'), 
									  ' is_executed=0 ');
		$exec = $database->query($query);
		if ($exec) {
			$record = $database->fetchArray($exec);
			$response = $this->sendMessage($record);

			return json_encode(array('status' => $response));
		}

	}

	/*
	* Private Methods
	*/

	/* 
	* Sending messages from a queue
	*/
	private function sendMessage($record){

			// Preparing message requests
			$messages = json_decode($record['message'], true);
			foreach ($messages as $messagePart){
				$request = array(
					'message' => $record['message'],
					'cellphone' => $record['cellphone'],
				);	

				// Sending Message
				$accessKey = Config::params('sms_service')->access_key;
				$originator = Config::params('sms_service')->originator;
				$MessageBird = new \MessageBird\Client($accessKey);
				$Message = new \MessageBird\Objects\Message();
				$Message->originator = $originator;
				$Message->recipients = $request['cellphone'];
				$Message->body = $request['message'];

				try {
					$MessageResult = $MessageBird->messages->create($Message);
					print_r($MessageResult);

						$id = $record['id'];
						$dataTime = Date('Y-m-d H:i:s');
						// Update message status


					return 'Message has been sent successfully.';
				} catch (\MessageBird\Exceptions\AuthenticateException $e) {
					// That means that your accessKey is unknown
					return 'SMS API Authentication Failed. Please contact to your Admininstrator.';
				} catch (\MessageBird\Exceptions\BalanceException $e) {
					// That means that you are out of credits, so do something about it.
					return 'SMS API Out of Credit. Please contact to your Admininstrator.';
				} catch (\Exception $e) {
					return $e->getMessage();
				}

			}

	}

}