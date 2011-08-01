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
 * Google Checkout Payment Driver
 *
 * @package    Payments
 * @author     Chase "Syntaqx" Hutchins
 * @author     Frank Bardon Jr. <frank@nerdsrescue.me>
 * @version    1.0
 */
class Payment_Driver_Googlecheckout implements Payment_Driver {

	/**
	 * Required driver fields to process a transaction
	 *
	 * @var   array
	 */
	protected $required_fields = array(
		'ENDPOINT'       => false,
		'MERCHANT_ID'    => false,
	);

	/**
	 * Provided fields values during this execution
	 *
	 * @var   array
	 */
	protected $fields = array(
		'ENDPOINT'          => '',
		'MERCHANT_ID'       => '',
	);

	/**
	 * Set configuration options for utilization within the class
	 *
	 * @param   array     Configuration, normally passed from the Payments class
	 */
	public function __construct(array $config = array())
	{
		//$this->curl_config = $config['googlecheckout']['curl_config'];

		parent::__construct($config);

		if($this->test_mode === true)
		{
			$this->set_fields(array(
				'MERCHANT_ID' => $config['googlecheckout']['SANDBOX_MERCHANT_ID'],
				'ENDPOINT'    => $config['googlecheckout']['SANDBOX_ENDPOINT'],
			));
		}
		else
		{
			$this->set_fields(array(
				'MERCHANT_ID' => $config['googlecheckout']['MERCHANT_ID'],
				'ENDPOINT'    => $config['googlecheckout']['ENDPOINT'],
			));
		}

		$this->set_fields(array(
			'VERSION'      => $config['googlecheckout']['VERSION'],
			'CURRENCYCODE' => $config['googlecheckout']['CURRENCYCODE'],
			'IPADDRESS'    => $_SERVER['REMOTE_ADDR'],
		));
	}

	/**
	 * Run the transaction
	 *
	 * @return    boolean
	 */
	public function process()
	{
		// Do transaction
	}
}

/* End of file googlecheckout.php */