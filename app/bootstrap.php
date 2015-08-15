<?php

// Include the composer autoloader
if(!file_exists(__DIR__ .'/../vendor/autoload.php')) {
	echo "The 'vendor' folder is missing. You must run 'composer update' to resolve application dependencies.\nPlease see the README for more information.\n";
	exit(1);
}
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/common/paypal.php';
require_once __DIR__ . '/common/util.php';
require_once __DIR__ . '/common/key.php';
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;

// Define connection parameters
define('MYSQL_HOST', 'localhost');
define('MYSQL_USERNAME', 'root');
define('MYSQL_PASSWORD', '12345');
define('MYSQL_DB', 'api_data');
define('API_USER','admin');
define('API_PASSWORD','admin');
define('ADMIN_USER','admin_123');
define('ADMIN_PASS', '12345%$#@!');
define('HOST','http://dungvt-002-site8.myasp.net/app/');
        

return getApiContext();

// SDK Configuration
function getApiContext() {


    // Define the location of the sdk_config.ini file
    if (!defined("PP_CONFIG_PATH")) {
        define("PP_CONFIG_PATH", dirname(__DIR__));
    }

	$apiContext = new ApiContext(new OAuthTokenCredential(
		'EBWKjlELKMYqRNQ6sYvFo64FtaRLRR5BdHEESmha49TM',
		'EO422dn3gQLgDbuwqTjzrFgFtaRLRR5BdHEESmha49TM'
	));

	
	// Alternatively pass in the configuration via a hashmap.
	// The hashmap can contain any key that is allowed in
	// sdk_config.ini	
	/*
	$apiContext->setConfig(array(
		'http.ConnectionTimeOut' => 30,
		'http.Retry' => 1,
		'mode' => 'sandbox',
		'log.LogEnabled' => true,
		'log.FileName' => '../PayPal.log',
		'log.LogLevel' => 'INFO'		
	));
	*/
	return $apiContext;
}
