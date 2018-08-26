<?php
require_once('config/config.php');
require_once('jobs/sendMessage.php');

	// Jobs will be executed from here, via crontab
	$action = ( isset($_GET['action']) ) ? $_GET['action'] : 'default';
	switch ($action) {
		case 'send_message':
			// Job to send messages, pick only 1 message per second
			$sendMessage = new SendMessages;
			echo $sendMessage->send();
			break;

		default:
			echo "Invalid Request Found.";
			break;
	}