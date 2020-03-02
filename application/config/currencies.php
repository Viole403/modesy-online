<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
| CURRENCIES
| -------------------------------------------------------------------
| This file contains an array of currencies.  It is used for
| the product prices
|
*/
require_once(BASEPATH . 'database/DB.php');
$db =& DB();
$currencies = $db->get('currencies')->result();
foreach ($currencies as $currency) {
	$config['currencies_array'][$currency->code] = array('name' => $currency->name, 'symbol' => $currency->symbol, 'hex' => $currency->hex);
}
$db->close();


