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

	/**
	 * Holds the active instance to the payment driver
	 *
	 * @var     \Payments\Payment
	 */
	protected static $instance;

	/**
	 * Disable the ability to construct the object
	 */
	final private function __construct() {}

	/**
	 * Disable the ability to clone the object
	 */
	final private function __clone() {}

	/**
	 * Initializes a new payment instance.
	 *
	 * @param   array            ...
	 * @return  object           Returns a new instance of the Payments driver
	 * @throws  \Fuel_Exception  If an invalid or no gateway is provided, an exception is thrown.
	 */
	public static function factory(array $config = array())
	{
		// TODO: Load the DRIVER configuration, not the global payments config. That's what we want to pass
		// to the driver.
		$config = array_merge($config, (array) \Config::load('payments', true));

		if(!isset($config['gateway']) or empty($config['gateway']))
		{
			throw new \Fuel_Exception('No payment gateway was specified, unable to instanciate driver.');
		}

		$driver = 'Payment_Driver_'.ucfirst($config['gateway']);

		if(!class_exists($driver))
		{
			throw new \Fuel_Exception('An invalid gateway ['.$config['gateway'].'] was provided. This driver does not exist! ['.$driver.']');
		}

		static::$instance = new $driver($config);

		return static::$instance;
	}

	/**
	 * Returns the active Payments driver instance or creates a new one if none exists
	 *
	 * @return  object           Returns an instance of a Payments driver
	 */
	public static function instance(array $config = array())
	{
		if(static::$instance === null)
		{
			static::factory($config);
		}

		return static::$instance;
	}
}

/* End of file payment.php */