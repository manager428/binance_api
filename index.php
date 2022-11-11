<?php
/*
 * Get Balance Binance Exchange
 * Documentation https://github.com/binance-exchange/binance-official-api-docs/blob/master/rest-api.md
 */
include('API.php');
/**
 * Get server time
 * the server time must be obtained to sign the requests curl
 * Time is the variable used for requests
 */
$ServerTimeUrl='https://api.binance.com/api/v1/time'; 
$ClassServerTime = new APIREST($ServerTimeUrl);
$CallServerTime = $ClassServerTime->call(array());
$DecodeCallTime= json_decode($CallServerTime);
$Time = $DecodeCallTime->serverTime;
$ApiKey='ApiKey'; // the Api key provided by binance
$ApiSecret='ApiSecret'; // the Secret key provided by binance
$Timestamp = 'timestamp='.$Time; // build timestamp type url get
$Signature = hash_hmac('SHA256',$Timestamp ,$ApiSecret); // build firm with sha256
/**
 * Get balance
 * @var BalanceUrl is the url of the request
 * @var ClassBalance initializes the APIREST class
 * @var CallBalance request balance sheets, X-MBX-APIKEY is required by binance api
 */
$BalanceUrl='https://api.binance.com/api/v3/account?timestamp='.$Time.'&signature='.$Signature;
$ClassBalance = new APIREST($BalanceUrl);
$CallBalance= $ClassBalance->call(
	array('X-MBX-APIKEY:'.$ApiKey)
);
$balances = json_decode($CallBalance);

foreach($balances->balances as $balance) {
	if ($balance->free > 0) {
		$response = file_get_contents('https://api.binance.com/api/v3/ticker/price?symbol=' . $balance->asset . 'USDT');
		$rates = json_decode($response);
		print_r('<h1 style="text-align:center">Binance Balance</h1>');
		print_r('<h2 style="text-align:center">Spot Wallet :' . $balance->free * $rates->price . ' USD</h2>');
	}
}

/** Binance Future Wallet **/
$ServerTimeUrl='https://fapi.binance.com/fapi/v1/time'; 
$ClassServerTime = new APIREST($ServerTimeUrl);
$CallServerTime = $ClassServerTime->call(array());
$DecodeCallTime= json_decode($CallServerTime);
$Time = $DecodeCallTime->serverTime;
$ApiKey='ApiKey'; // the Api key provided by binance
$ApiSecret='ApiSecret'; // the Secret key provided by binance
$Timestamp = 'timestamp='.$Time; // build timestamp type url get
$Signature = hash_hmac('SHA256',$Timestamp ,$ApiSecret); // build firm with sha256
/**
 * Get balance
 * @var BalanceUrl is the url of the request
 * @var ClassBalance initializes the APIREST class
 * @var CallBalance request balance sheets, X-MBX-APIKEY is required by binance api
 */
$BalanceUrl='https://fapi.binance.com/fapi/v1/account?timestamp='.$Time.'&signature='.$Signature;


$ClassBalance = new APIREST($BalanceUrl);
$CallBalance= $ClassBalance->call(
	array('X-MBX-APIKEY:'.$ApiKey)
);
$balances = json_decode($CallBalance);
print_r('<h2 style="text-align:center">Future Wallet: ' . $balances->totalWalletBalance . ' USD</h2>');
?>