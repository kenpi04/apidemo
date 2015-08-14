<?php
/*
 * Store user is redirected here once he has chosen
 * a payment method. 
 */
require_once __DIR__ . '/../bootstrap.php';
session_start();
if(!isOrdered()) {
	header('Location: ../index.php');
	exit;
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {	
	$order = $_POST["order"];
	try {				
			$orderInfo=  getOrderInfo();
			//$recoderId = insertKey($orderInfo["email"],$orderInfo["ime"],$orderInfo["deviceid"],$orderInfo["devicetype"]);
			// Create the payment and redirect buyer to paypal for payment approval. 
                        $recoderId=0;
			$baseUrl = getBaseUrl() . "/order_completion.php?paymentId=$recoderId";
			$payment = makePaymentUsingPayPal($order['amount'], 'USD', $order['description'],
					"$baseUrl&success=true", "$baseUrl&success=false");

			header("Location: " . getLink($payment->getLinks(), "approval_url") );
			exit;			
		
	} catch (\PayPal\Exception\PPConnectionException $ex) {
		$message = parseApiError($ex->getData());
		$messageType = "error";
	} catch (Exception $ex) {
		$message = $ex->getMessage();
		$messageType = "error";
	}
}
require_once 'index.php';