<?php

/**
 * Payments
 *
 * The payments package allows you to easily process e-commerce transactions
 * without having to worry about all the backend details of connecting and
 * settings up specifications regarding various payment portals.
 *
 * @package    Payments
 * @version    1.0
 * @author     Ninjarite Development Group
 * @license    MIT License
 * @copyright  2011 Ninjarite Development
 */
namespace Payments;

/**
 * Authorize.net Payment Driver
 *
 * @package    Payments
 * @author     Chase "Syntaqx" Hutchins
 * @author     Frank Bardon Jr. <frank@nerdsrescue.me>
 * @version    1.0
 */
class Payment_Driver_Authorize extends Payment_Driver {

	/**
	 * Required driver fields to process a transaction
	 *
	 * @var   array
	 */
	protected $required_fields = array(
		// Expected
		'x_login'           => false,
		'x_tran_key'        => false,
		'x_card_num'        => false,
		'x_exp_date'        => false,
		'x_amount'          => false,
		// Set by default values
		'x_version'         => true,
		'x_delim_char'      => true,
		'x_url'             => true,
		'x_type'            => true,
		'x_method'          => true,
		'x_relay_response'  => true,
	);

	/**
	 * Provided fields values during this execution
	 *
	 * @var   array
	 */
	protected $fields = array(
		'x_version'         => '3.1',
		'x_delim_char'      => '|',
		'x_delim_data'      => 'TRUE',
		'x_url'             => 'FALSE',
		'x_type'            => 'AUTH_CAPTURE',
		'x_method'          => 'CC',
		'x_relay_response'  => 'FALSE',
	);

	/**
	 * Set configuration options for utilization within the class
	 *
	 * @param   array     Configuration, normally passed from the Payments class
	 */
	public function __construct(array $config = array())
	{
		$this->set_fields(array(
			'login'    => $config['authorize']['auth_net_login_id'],
			'tran_key' => $config['authorize']['auth_net_tran_key'],
		));

		parent::__construct($config);
	}

	/**
	 * Sets the driver fields and marks required fields as true
	 *
	 * @param   array     array of key => value pairs to set
	 * @return  object    \Payments\Payments_Driver
	 */
	public function set_fields(array $fields = array())
	{
		foreach((array) $fields as $key => $value)
		{
			// Prepend every $key with "x_" for convenience 
			$fields['x_'.$key] = $value;
			unset($fields[$key]);
		}

		parent::set_fields($fields);
	}

	/**
	 * Run the transaction
	 *
	 * @return   boolean
	 */
	public function process()
	{
		// Validate required fields have been set
		if(in_array(false, $this->required_fields))
		{
			$fields = array();

			foreach($this->required_fields as $key => $field)
			{
				if($field === false)
				{
					$fields[] = $key;
				}
			}

			throw new \Fuel_Exception(\Lang::line('payments.required', array('fields' => implode(', ', $fields))));
		}

		$fields = '';

		foreach($this->fields as $key => $value)
		{
			$fields .= $key.'='.urlencode($value).'&';
		}

		$url = ($this->test_mode) ? 'https://certification.authorize.net/gateway/transact.dll' : 'https://secure.authorize.net/gateway/transact.dll';

		$curl = curl_init($url);

		curl_setopt_array($curl, $this->curl_config);
		curl_setopt($curl, CURLOPT_POSTFIELDS, rtrim($fields, '&'));

		$response = curl_exec($curl);

		curl_close($curl);
		
		if(!$response)
		{
			throw new PaymentGatewayException('Connection error into payments gateway..');
		}

		// This could probably be done better, but it's taken right from the
		// Authorize.net manual. Need testing to optimize properly.
		$heading = substr_count($response, '|');

		for($i = 1; $i <= $heading; $i++)
		{
			$delimiter_position = strpos($response, '|');

			if($delimiter_position !== False)
			{
				$response_code = substr($response, 0, $delimiter_position);
				$response_code = rtrim($response_code, '|');

				if($response_code == '')
				{
					throw new PaymentGatewayException('payment.gateway_connection_error');
				}

				switch($i)
				{
					case 1:
					{
						$this->response    = (($response_code == '1') ? explode('|', $response) : false); // Approved
						$this->transaction = true;

						return $this->transaction;
					}
					default:
					{
						$this->transaction = false;

						return $this->transaction;
					}
				}
			}
		}
	}
}

/* End of file authorize.php */