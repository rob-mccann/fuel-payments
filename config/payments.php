<?php

return array(

	/**
	 * Default gateway, when non is specified
	 *
	 * @var    string
	 */
	'gateway'       => 'paypalpro',

	/**
	 * Whether we're using test mode or production
	 *
	 * @var    boolean
	 */
	'test_mode'     => true,

	/**
	 * Standard CURL configurations passed to all drivers
	 *
	 * @var    array
	 */
	'curl_config'   => array(
		CURLOPT_HEADER          => false,
		CURLOPT_RETURNTRANSFER  => true,
		CURLOPT_SSL_VERIFYPEER  => false,
	),

	/**
	 * Authorize.net Gateway Configurations
	 *
	 * @var   array
	 */
	'authorize'     => array(
		'auth_net_login_id' => '', // Your transaction login ID - Provided by gateway provider
		'auth_net_tran_key' => '', // Your transaction key - Provided by gateway provider
	),

	/**
	 * PayPal Payments Pro Options
	 *
	 * @var    array
	 */
	'paypalpro'     => array(
		// Authorization Credentials
		'USER'              => '', // API Username
		'PWD'               => '', // API Password
		'SIGNATURE'         => '', // API Signature
		'ENDPOINT'          => 'https://api-3t.paypal.com/nvp', // API url for live transactions

		// Sandbox Mode
		'SANDBOX_USER'      => 'sdk-three_api1.sdk.com', // Sandbox API username
		'SANDBOX_PWD'       => 'QFZCWN5HZM8VBG7Q', // Sandbox API password
		'SANDBOX_SIGNATURE' => 'A.d9eRKfd1yVkRrtmMfCFLTqa6M9AyodL0SJkhYztxUi8W9pCXF6.4NI', // Sandbox API signature
		'SANDBOX_ENDPOINT'  => 'https://api-3t.sandbox.paypal.com/nvp', // API url for sandbox transactions

		// API Specific Configurations
		'VERSION'           => '3.2', // NVP version
		'CURRENCYCODE'      => 'USD', // Currency code

		// CURL Configurations
		'curl_config'       => array(
			CURLOPT_SSL_VERIFYHOST => false,
			CURLOPT_VERBOSE        => true,
			CURLOPT_POST           => true,
		),
	),

);

/* End of file payments.php */