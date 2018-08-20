<?php
require_once('config/config.php');
require_once('lib/message.php');
require_once('jobs/send_message.php');

	// Jobs will be executed from here, via crontab
	switch ($_GET['action']) {
		case 'send_message':
			// Job to send messages, pic only 1 message per second
			$sendMessage = new SendMessages;
			echo $sendMessage->send();
			break;

		default:
			echo "Invalid Request Found.";
			break;
	}