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
Autoloader::add_core_namespace('Payments');

// Define available classes into the Autoloader
Autoloader::add_classes(array(
	'Payments\\Payment'                         => __DIR__.'/classes/payment.php',
	'Payments\\Payment_Driver'                  => __DIR__.'/classes/payment/driver.php',
	'Payments\\Payment_Driver_Authorize'        => __DIR__.'/classes/payment/driver/authorize.php',
	'Payments\\Payment_Driver_Googlecheckout'   => __DIR__.'/classes/payment/driver/googlecheckout.php',
	'Payments\\Payment_Driver_Moneybookers'     => __DIR__.'/classes/payment/driver/moneybookers.php',
	'Payments\\Payment_Driver_Paypal'           => __DIR__.'/classes/payment/driver/paypal.php',
	'Payments\\Payment_Driver_Paypalpro'        => __DIR__.'/classes/payment/driver/paypalpro.php',
	'Payments\\Payment_Driver_Trident'          => __DIR__.'/classes/payment/driver/trident.php',
	'Payments\\Payment_Driver_Trustcommerce'    => __DIR__.'/classes/payment/driver/trustcommerce.php',
	'Payments\\Payment_Driver_Yourpay'          => __DIR__.'/classes/payment/driver/yourpay.php',
));

/* End of file bootstrap.php */