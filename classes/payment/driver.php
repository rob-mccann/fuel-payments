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

	/**
	 * Required driver fields to process a transaction
	 *
	 * @var   array
	 */
	protected $required_fields = array();

	/**
	 * Provided fields values during this execution
	 *
	 * @var   array
	 */
	protected $fields = array();

	/**
	 * Runtime error cache
	 *
	 * @var   array
	 */
	protected $errors = array();

	/**
	 * Whether to run the payment as a test or live transaction
	 *
	 * @var   boolean
	 */
	protected $test_mode = true;

	/**
	 * Response codes set from the gateway
	 *
	 * @var   array
	 */
	protected $response;

	/**
	 * Whether a transaction has been completed
	 *
	 * @var   boolean
	 */
	protected $transaction = false;

	/**
	 * Set configuration options for utilization within the class
	 *
	 * @param   array     Configuration, normally passed from the Payments class
	 */
	public function __construct(array $config = array())
	{
		$this->test_mode   = $config['test_mode'];
		$this->curl_config = $config['curl_config'];
	}

	/**
	 * Sets the driver fields and marks required fields as true
	 *
	 * @param   array     array of key => value pairs to set
	 * @return  object    \Payments\Payments_Driver
	 */
	public function set_fields(array $fields = array())
	{
		$this->fields = array_merge($fields, $this->fields);

		// If a required key was provided, switch the boolean
		foreach($fields as $key => $value)
		{
			if(isset($key, $this->required_fields) and !empty($value))
			{
				$this->required_fields[$key] = true;
			}
		}

		return $this;
	}

	/**
	 * Add an error to the errors cache
	 *
	 * @param   integer   Error code
	 * @param   string    Error message
	 * @return  object    \Payments\Payments_Driver
	 */
	public function set_error(integer $code, string $message)
	{
		$this->errors[] = array(
			'code'    => $code,
			'message' => $message,
		);

		return $this;
	}

	/**
	 * Return the entire errors set
	 *
	 * @return  array     Any errors that have occured
	 */
	public function get_errors()
	{
		return $this->errors;
	}

	/**
	 * Get the last error that occured
	 *
	 * @return   array    The last error that occured
	 */
	public function get_last_error()
	{
		return end($this->errors);
	}

	/**
	 * Retrieves the response array from a successful transaction
	 *
	 * @return   array|boolean
	 */
	public function get_response()
	{
		if($this->transaction !== false)
		{
			return $this->response;
		}

		return false;
	}

	/**
	 * Run the transaction
	 *
	 * @return   boolean
	 */
	abstract public function process();
}

/* End of file driver.php */