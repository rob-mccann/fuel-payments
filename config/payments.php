<?php

return array(

	// Default payments values
	'driver'        => 'authorize',
	'test_mode'     => true,
	'curl_config'   => array(
		CURLOPT_HEADER          => false,
		CURLOPT_RETURNTRANSFER  => true,
		CURLOPT_SSL_VERIFYPEER  => false,
	),

	// Authorize.net Options
	'authorize'     => array(
		'auth_net_login_id' => '', // Your transaction login ID - Provided by gateway provider
		'auth_net_tran_key' => '', // Your transaction key - Provided by gateway provider
	),
);

/* End of file payments.php */