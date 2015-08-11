<?php
/*
 * Order listing page. We rely on the local database
 * to retrieve the order history of this buyer. 
 */
require_once __DIR__ . '/../bootstrap.php';
if(!isset($_SESSION)) {
    session_start();
}
if(!isSignedIn()) {
    header('Location: ../user/sign_in.php');
    exit;
}
$paymentDetails = null;
try {
    if (isset($_REQUEST['payment_id']) && trim($_REQUEST['payment_id']) != '') {
        $paymentDetails = getPaymentDetails($_REQUEST['payment_id']);
    }
} catch (\PayPal\Exception\PPConnectionException $ex) {
    $message = parseApiError($ex->getData());
    $messageType = "error";
} catch (Exception $ex) {
    $message = $ex->getMessage();
    $messageType = "error";
}
?>
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='utf-8'>
    <meta content='IE=Edge,chrome=1' http-equiv='X-UA-Compatible'>
    <meta content='width=device-width, initial-scale=1.0' name='viewport'>
    <title>PizzaShop</title>
    <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
    <!--[if lt IE 9]>
    <script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.6.1/html5shiv.js" type="text/javascript"></script>
    <![endif]-->
    <link href="../../public/css/application.css" media="all" rel="stylesheet"
          type="text/css" />
    <link href="../../public/images/favicon.ico" rel="shortcut icon"
          type="image/vnd.microsoft.icon" />
</head>
<body>
<?php include '../navbar.php';?>
<div class='container' id='content'>
    <?php if(isset($message) && isset($messageType)) {?>
        <div class="alert fade in alert-<?php echo $messageType;?>">
            <button class="close" data-dismiss="alert">&times;</button>
            <?php echo $message;?>
        </div>
    <?php }?>
    <br />
    <a href="orders.php" >&#10094; Back to Orders</a>
    <h2>Payment Details</h2>
		<pre>
            <?php
                if (defined("JSON_PRETTY_PRINT")) {
                    echo $paymentDetails->toJSON(JSON_PRETTY_PRINT);
                }
                else {
                    echo $paymentDetails->toJSON();
                } ?>
		</pre>
</div>
<?php include '../footer.php';?>
<script src="../../public/js/application.js" type="text/javascript"></script>
</body>
</html>
