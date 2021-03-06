<?php

class Config {

	function params($param=''){

		$config = array(

			// Database
			'db_host' => 'localhost',
			'db_name' => 'messagebird',
			'db_username' => 'root',
			'db_password' => '10pearls',

			// Application
			'ip_daily_message_limit' => 10,
			'max_size' => 160,
			'min_full_message_length' => 10,
			'max_full_message_length' => 320,
			'min_country_code' => 2,
			'min_cellphone_number' => 9,
			'status' => (object) array(
						'sent' => 'sent',
						'failed' => 'failed',
						'invalid_request' => 'invalid_request',
				   ),

			// SMS Services
			'sms_service' => (object) array(
						 // 'access_key' => 'N9rxIiH6XiPJTK9aPm93WahRq', // TEST
						 'access_key' => '9J5pVGhXSsKaGIsfRB5oyB0YS',
						 'originator' => 'MessageBird',
						 'type' => 'binary',
						 'datacoding' => 'unicode'
					),


		);

		return $config[$param];
	}

}