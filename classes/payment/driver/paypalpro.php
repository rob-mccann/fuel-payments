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
 * PayPal (Website Payments Pro) Payment Driver
 *
 * @package    Payments
 * @author     Chase "Syntaqx" Hutchins
 * @version    1.0
 */
class Payment_Driver_Paypalpro extends Payment_Driver {

	/**
	 * Required driver fields to process a transaction
	 *
	 * @var   array
	 */
	protected $required_fields = array(
		'ENDPOINT'       => false,
		'USER'           => false,
		'PWD'            => false,
		'SIGNATURE'      => false,
		'VERSION'        => false,
		'METHOD'         => false,
		'PAYMENTACTION'  => false,
		'CURRENCYCODE'   => false, // default is USD - only required if other currency needed
		'AMT'            => false, // payment amount
		'IPADDRESS'      => false,
		'FIRSTNAME'      => false,
		'LASTNAME'       => false,
		'CREDITCARDTYPE' => false,
		'ACCT'           => false, // card number
		'EXPDATE'        => false,
		'CVV2'           => false,
	);

	/**
	 * Provided fields values during this execution
	 *
	 * @var   array
	 */
	protected $fields = array(
		'ENDPOINT'          => '',
		'USER'              => '',
		'PWD'               => '',
		'SIGNATURE'         => '',
		'VERSION'           => '',
		/**
		 * Some of the possible values for METHOD:
		 *     'DoDirectPayment',
		 *     'RefundTransaction',
		 *     'DoAuthorization',
		 *     'DoReauthorization',
		 *     'DoCapture',
		 *     'DoVoid'
		 */
		'METHOD'            => '',
		/**
		 * Some possible values for PAYMENTACTION:
		 *     'Sale'
		 *     'Authorization'
		 */
		'PAYMENTACTION'     => '',
		'CURRENCYCODE'      => '',
		'AMT'               => 0,  // payment amount
		'IPADDRESS'         => '',
		'FIRSTNAME'         => '',
		'LASTNAME'          => '',
		'CREDITCARDTYPE'    => '',
		'ACCT'              => '', // card number
		'EXPDATE'           => '', // Format: MMYYYY
		'CVV2'              => '', // security code
		// -- OPTIONAL FIELDS --
		'STREET'            => '',
		'STREET2'           => '',
		'CITY'              => '',
		'STATE'             => '',
		'ZIP'               => '',
		'COUNTRYCODE'       => '',
		'SHIPTONAME'        => '',
		'SHIPTOSTREET'      => '',
		'SHIPTOSTREET2'     => '',
		'SHIPTOCITY'        => '',
		'SHIPTOSTATE'       => '',
		'SHIPTOZIP'         => '',
		'SHIPTOCOUNTRYCODE' => '',
		'INVNUM'            => '' // your internal order id / transaction id
		// other optional fields listed here:
		// https://www.paypal.com/en_US/ebook/PP_NVPAPI_DeveloperGuide/Appx_fieldreference.html#2145100
	);

	/**
	 * Set configuration options for utilization within the class
	 *
	 * @param   array     Configuration, normally passed from the Payments class
	 */
	public function __construct(array $config = array())
	{
		$this->curl_config = $config['paypalpro']['curl_config'];

		parent::__construct($config);

		if($this->test_mode === true)
		{
			$this->set_fields(array(
				'USER'      => $config['paypalpro']['SANDBOX_USER'],
				'PWD'       => $config['paypalpro']['SANDBOX_PWD'],
				'SIGNATURE' => $config['paypalpro']['SANDBOX_SIGNATURE'],
				'ENDPOINT'  => $config['paypalpro']['SANDBOX_ENDPOINT'],
			));
		}
		else
		{
			$this->set_fields(array(
				'USER'      => $config['paypalpro']['USER'],
				'PWD'       => $config['paypalpro']['PWD'],
				'SIGNATURE' => $config['paypalpro']['SIGNATURE'],
				'ENDPOINT'  => $config['paypalpro']['ENDPOINT'],
			));
		}

		$this->set_fields(array(
			'VERSION'      => $config['paypalpro']['VERSION'],
			'CURRENCYCODE' => $config['paypalpro']['CURRENCYCODE'],
		));
	}

	/**
	 * Run the transaction
	 *
	 * @return    boolean
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

		// Temporary fields to be passed
		$fields = $this->fields;

		// Instanciate curl and pass the API post url
		$curl = curl_init($fields['ENDPOINT']);

		foreach($fields as $key => $value)
		{
			// Don't include unset optional fields in the name-value pair request string
			if(empty($value) or $key == 'ENDPOINT')
			{
				unset($fields[$key]);
			}
		}

		curl_setopt_array($curl, $this->curl_config);
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($fields));

		$response = curl_exec($curl);

		if(curl_errno($curl))
		{
			$error_num = curl_errno($curl);
			$error_msg = curl_error($curl);
		}

		curl_close($curl);

		if(isset($error_num) and isset($error_msg))
		{
			throw new \Fuel_Exception(\Lang::line('payments.error', array('error' => '['.$error_num.'] '.$error_msg)));
		}

		if(!$response)
		{
			throw new \Fuel_Exception(\Lang::line('payments.gateway_connection_error'));
		}

		$response_array = array();

		parse_str(urldecode($response), $response_array);

		$this->transaction = strtolower($response_array['ACK']) != 'failure';
		$this->response    = $response_array();

		if(!$this->transaction)
		{
			$this->set_error($response_array['L_ERRORCODE0'], $response_array['L_LONGMESSAGE0']);
			return false;
		}

		return true;
	}
}

/* End of file paypalpro.php */