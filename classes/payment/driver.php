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
 * Payment driver
 *
 * @package    Payments
 * @author     Chase "Syntaqx" Hutchins
 * @author     Frank Bardon Jr. <nerdsrescueme>
 * @version    1.0
 */
abstract class Payment_Driver {

	protected $fields = array(
		'card_number' => 0,
		'card_expires' => 'mm/yy',
		'card_cvv' => 0,
		'description' => '',
		'amount' => 0,
		'tax' => 0,
		'shipping' => 0,
		'name' => array(
			'first' => '',
			'last' => '',
		),
		'company' => '',
		'address' => '',
		'city' => '',
		'state' => '',
		'zip' => 0,
		'ship_to' => array(
			'name' => array(
				'first' => '',
				'last' => '',
			),
			'company' => '',
			'address' => '',
			'city' => '',
			'state' => '',
			'zip' => 0,
		),
		'email' => '',
		'phone' => 0,
		'fax' => 0,
	);

	/**
	 * Which fields are required by this service provider?
	 *
	 * @access protected
	 */
	protected $required_fields = array();

	/**
	 * Error message storage.
	 *
	 * @access protected
	 */
	protected $errors = array();

	/**
	 * Request body to be sent
	 *
	 * @access protected
	 */
	protected $_request = null;


	/**
	 * Sets data within the driver to be sent to the service
	 * provider in order to fulfill the payment request.
	 *
	 * @param  array fields to be set
	 * @return Payment_Driver
	 */
	public function set_fields(array $fields)
	{
		$this->fields = array_merge($fields, $this->fields);
		
		return $this;
	}

	/**
	 * Send the request to the payment gateway. It will render
	 * the request if one is not present.
	 *
	 * return boolean
	 */
	public function send()
	{
		if ( ! $this->_request)
		{
			$this->_build_request();
		}
		return $this->_send();
	}

	/**
	 * The actual send logic
	 */
	protected abstract function _send();

	/**
	 * Build the request string to be sent to the payment gateway.
	 *
	 * @return Payment_Driver
	 */
	public function request()
	{
		$this->_request = $this->_build_request();
		
		return $this;
	}

	/**
	 * The actual request logic.
	 */
	protected abstract function _build_request();

	/**
	 * Add an error to the errors array
	 *
	 * @param  integer error code
	 * @param  string  description of the error
	 * @return Payment_Driver
	 */
	public function set_error(integer $code, string $description)
	{
		$this->errors[$code] = $description;
		
		return $this;
	}

	/**
	 * Check for an error by code and return its value if
	 * it exists in the errors array.
	 *
	 * @param integer     lookup code
	 * @return array|null specific error or nothing
	 */
	public function error(integer $code)
	{
		if (array_key_exists($code, $this->errors))
		{
			return $this->errors[$code];
		}
		return null;
	}

	/**
	 * Return the entire errors array
	 *
	 * @return array all errors present
	 */
	public function errors()
	{
		return $this->errors();
	}
}

/* End of file driver.php */