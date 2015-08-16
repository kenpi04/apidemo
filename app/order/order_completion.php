<?php
/*
 * Order completion page. When PayPal is used as the payment method,
 * the buyer gets redirected here post approval / cancellation of
 * payment.
 */
require_once __DIR__ . '/../bootstrap.php';
session_start();
if(!isOrdered()) {
	header('Location: ../index.php');
	exit;
}
$basePath = (strstr($_SERVER['PHP_SELF'], "/index.php")) ? "." : "..";

if(isset($_GET['success'])) {

	// We were redirected here from PayPal after the buyer approved/cancelled
	// the payment
	if($_GET['success'] == 'true' && isset($_GET['PayerID']) && isset($_GET['orderid'])) {
			$orderinfo=getOrderInfo();			
            $orderId = trim($_GET['orderid']);
            $payerId=$_GET['PayerID'];
            if(strcmp($orderId,$orderinfo['id'])!=0)
            {
            		$messageType = "error";
            		$message="Session time out!Payment was cancelled.!";

            }
            else
			{
          				
					try {	
						$extent="";
						
			           	if(isset($orderinfo["key"]))
			           	{
			           		$keyresult=updateKey($orderinfo["key"],$orderinfo["daylimit"],$orderinfo["price"],$payerId);
			           		
			           		$extent="had been extentsion successfull!";				
			           	}
			           	else
			           	{
							$keyresult = insertKey($orderinfo["email"], $orderinfo["ime"], $orderinfo["deviceid"], $orderinfo["devicetype"],$payerId,$orderinfo['daylimit'],$orderinfo['price']);
						}
						if(!$keyresult)
						{
							throw new Exception("Error when processing! Please contact admin");
							
						}
						$payment = executePayment($orderinfo['payment_id'],$payerId);  
						$messageType = "success";                       
						$message = "Your payment was successful. Your license key is <strong style='color:red'> $keyresult</strong>";
						if($extent!="")
							$message.=" ".$extent; 
						
				} catch (\PayPal\Exception\PPConnectionException $ex) {
						$message = parseApiError($ex->getData());
						$messageType = "error";
					} catch (Exception $ex) {
						$message = $ex->getMessage();
						$messageType = "error";
					}
			}
		
	} else {
				
		$messageType = "error";
		$message = "Your payment was cancelled.";
                
	}
	unset($_SESSION['orderinfo']);
}
?>
<!DOCTYPE html>
<html lang='en'>
<head>
<meta charset='utf-8'>
<meta content='IE=Edge,chrome=1' http-equiv='X-UA-Compatible'>
<meta content='width=device-width, initial-scale=1.0' name='viewport'>
<title>Buy key- Success</title>
<!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
<!--[if lt IE 9]>
<script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.6.1/html5shiv.js" type="text/javascript"></script>
<![endif]-->
<link href="../../public/css/application.css" media="all" rel="stylesheet"
	type="text/css" />
</head>
<body>
	<?php include '../navbar.php';?>
	<div class='container' id='content'>
		<?php if(isset($message) && isset($messageType)) {?>
		<div class="alert fade in alert-<?php echo $messageType;?>">
			<button class="close" data-dismiss="alert">&times;</button>
			<?php echo $message;?> <a href="<?php echo $basePath; ?>">return Home</a>

		</div>
		<?php }?>
		
	</div>
	<?php include '../footer.php';?>
	<script src="../../public/js/application.js" type="text/javascript"></script>
</body>
</html>

