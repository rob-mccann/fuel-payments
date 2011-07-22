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
 * Provides payment support for credit cards and other providers like PayPal.
 *
 * @package    Payments
 * @author     Chase "Syntaqx" Hutchins
 * @author     Frank Bardon Jr. <nerdsrescueme>
 * @version    1.0
 */
class Payment {

	protected static $_instance = null;

	public static instance()
	{
		if (static::$_instance === null)
		{
			static::$_instance = static::factory();
		}
		return static::$_instance;
	}

	/**
	 * Create a new instance of the payment driver.
	 *
	 * @param array configuration
	 */
	public static factory($config = array())
	{
		$initconfig = Config::load('payments');
		
		if (is_array($initconfig) and is_array($config))
		{
			$config = array_merge($initconfig, $config);
		}

		$gateway = ucfirst($config['gateway']);
		$driver = 'Payment_Driver_' . $gateway;
		
		if ( ! class_exists($driver))
		{
			throw new \Fuel_Exception("Payment gateway $gateway is not valid.");
		}
		
		return new $class($config);
	}

	/**
	 * Set fields needed for payment request
	 * 
	 * @param array fields to be added
	 */
	public static function set_fields(array $fields)
	{
		return static::instance()->set_fields($fields);
	}

	/**
	 * Build the request to be sent.
	 */
	public static function request()
	{
		return static::instance()->request();
	}

	/**
	 * Send the request to the payment gateway.
	 */
	public static function send()
	{
		return static::instance()->send();
	}

	/**
	 * Set an error in the errors array
	 *
	 * @param integer error code
	 * @param string  description of error
	 */
	public static function set_error(integer $code, string $description)
	{
		return static::instance()->set_error($code, $description);
	}

	/**
	 * Get an error by its code.
	 *
	 * @param integer code requested
	 */
	public static function error(integer $code)
	{
		return static::instance()->error($code);
	}

	/**
	 * Return all errors in an array.
	 */
	public static function errors()
	{
		return static::instance()->errors();
	}
}

/* End of file payment.php */