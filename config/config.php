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
			'max_size' => 150,
			'status' => (object) array(
					'sent' => 'sent',
					'failed' => 'failed',
				   ),



		);

		return $config[$param];
	}

}