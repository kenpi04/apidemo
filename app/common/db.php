<?php

/**
 * 
 * Common DB utilities
 */


define('KEY_TABLE', 'key_info');
define('PRICE_TABLE','prices');

/**
 * Returns a new mysql conncetion
 * @throws Exception
 * @return unknown
 */
function getConnection() {
	
$tableKey="CREATE TABLE IF NOT EXISTS `key_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(60) NOT NULL,
  `IME_Code` varchar(15) NOT NULL,
  `Device_Id` varchar(60) NOT NULL,
  `Type` smallint(6) NOT NULL,
  `key_code` varchar(50) NOT NULL,
  `CreateDate` datetime NOT NULL,
  `PayerId` varchar(50) DEFAULT NULL,
  `PaymentState` varchar(10) DEFAULT NULL,
  `DateLimit` datetime NOT NULL,
   `Price` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7";
$tablePrice="CREATE TABLE IF NOT EXISTS `prices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `MonthNumber` int NOT NULL,
  `Price` float NOT NULL,
 
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7";

	$link = @mysql_connect(MYSQL_HOST, MYSQL_USERNAME, MYSQL_PASSWORD);
	if(!$link) {
		throw new Exception('Could not connect to mysql ' . mysql_error() . PHP_EOL . 
				'. Please check connection parameters in app/bootstrap.php');
	}
	if(!mysql_select_db(MYSQL_DB, $link)) {
		throw new Exception('Could not select database ' . mysql_error() . PHP_EOL . 
				'. Please check connection parameters in app/bootstrap.php');
	}
	
	mysql_query($tableKey, $link);
	mysql_query($tablePrice,$link);
	return $link;
}