<?php
require_once('services/database.php');
require_once('config/config.php');

require_once(__DIR__ . '/../vendors/autoload.php');

class SendMessages {

	const FAILED = 'failed';
	const SUCCESS = 'success';

	/*
	* Executing Sending Messages Job
	*/
	public function send(){
		
		$database = new Database();
		$data = array(
			'cellphone' => $to,
			'message' => json_encode($messages),
			'ip_address' => $_SERVER['REMOTE_ADDR'],
		);

		$query = $database->selectOne('message_queues', array('id',
									  'is_executed','executed_at','message','cellphone'), 
									  ' is_executed=0 ');
		$exec = $database->query($query);
		if ($exec) {
			$record = $database->fetchArray($exec);
			$response = $this->sendMessage($database, $record);

			return json_encode(array('status' => $response['status'], 'message' => $response['message']));
		}

	}

	/*
	* Private Methods
	*/

	/* 
	* Sending messages from a queue
	*/
	private function sendMessage($database, $record){

			// Preparing message requests
			$messages = json_decode($record['message'], true);

			// Add headers for multiple parts
			$chunksId = '';
			if (count($messages)>1) $chunksId = md5(strtotime("now"));

			// Sending long messages in chunks and short in a single go
			foreach ($messages as $messagePart){

				$request = array(
					'message' => $messagePart,
					'cellphone' => $record['cellphone'],
				);	

				// Sending Message
				$accessKey = Config::params('sms_service')->access_key;
				$originator = Config::params('sms_service')->originator;
				$smsType = Config::params('sms_service')->type;
				$datacoding = Config::params('sms_service')->datacoding;

				$MessageBird = new \MessageBird\Client($accessKey);
				$Message = new \MessageBird\Objects\Message();
				$Message->originator = $originator;
				$Message->recipients = $request['cellphone'];
				$Message->body = $request['message'];
				$Message->type = $smsType;
				if ($chunksId) $Message->typeDetails['udh'] = $chunksId;
				$Message->datacoding = 'unicode';

				try {
					$MessageResult = $MessageBird->messages->create($Message);
					//print_r($MessageResult);
				} catch (\MessageBird\Exceptions\AuthenticateException $e) {
					// That means that your accessKey is unknown
					return array ('status' => self::FAILED, 'message' => 'SMS API Authentication Failed. Please contact to your Admininstrator.');
				} catch (\MessageBird\Exceptions\BalanceException $e) {
					// That means that you are out of credits, so do something about it.
					return array ('status' => self::FAILED, 'message' => 'SMS API Out of Credit. Please contact to your Admininstrator.');
				} catch (\MessageBird\Exceptions\RequestException $e) {
					// That means that either submitted request is invalid or sms provider not supporting that country
					// updating request status as failed
					$this->updateRecord($database, $record, 2);
					return array ('status' => self::FAILED, 'message' => $e->getMessage());
				} catch (\Exception $e) {
					return array ('status' => self::FAILED, 'message' => $e->getMessage());
				}

			}

			return $this->updateRecord($database, $record);
	}

	/* 
	* Update sms entry in queue after sending it
	*/
	private function updateRecord($database, $record, $status=1){

		$id = $record['id'];

		$recordValues = array ( 
			"is_executed" => $status, 
			"executed_at" => Date('Y-m-d H:i:s'),
		);

		// Update message status and execution time to log
		$query = $database->update('message_queues', $recordValues, " id = $id ");
		$database->query($query);

		return array ('status' => self::SUCCESS, 'message' => 'Message has been sent successfully.');
	}

}