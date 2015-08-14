<?php
/*
 * An order confirmation screen for the buyer. The buyer
 * is required to choose a payment method here.
 * Available payment methods are paypal and credit card.
 */
require_once __DIR__ . '/../bootstrap.php';
session_start();
//set amount for lisence
if (!isOrdered()) {
    header('Location: ../index.php');
    exit;
}
 $orderInfo = getOrderInfo();
$amount=0;
$description="";
$prices=getPriceById($orderInfo['price_id']);
print_r($prices)
exit();
if(!$prices)
{
    $errorMessage="Processing error! License type not found";
    $messageType = "error";  
}
else
{

$amount = $prices['Price'];
$description =  $prices["MonthNumber"]." Months";
 $_SESSION['orderinfo']['price']=$amount;
 $_SESSION['orderinfo']['daylimit']=(int)$prices['MonthNumber']*30;

// Figure out what funding instruments are available for this buyer


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    try {

        $order = $_POST["order"];
       
        $id=$orderInfo['id'];
      /*  try {
            $recoderId = insertKey($orderInfo["email"], $orderInfo["ime"], $orderInfo["deviceid"], $orderInfo["devicetype"]);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $messageType = "error";
        }*/
// Create the payment and redirect buyer to paypal for payment approval. 
        if (isset($id)) {
            $baseUrl = getBaseUrl() . "/order_completion.php?orderid=$id";
            $payment = makePaymentUsingPayPal($order['amount'], 'USD', $order['description'], "$baseUrl&success=true", "$baseUrl&success=false");
            $_SESSION['orderinfo']['payment_id']=$payment->getId();
            header("Location: " . getLink($payment->getLinks(), "approval_url"));
            exit;
        }
    } catch (\PayPal\Exception\PPConnectionException $ex) {
        $message = parseApiError($ex->getData());
        $messageType = "error";
    }
}
}
?>
<!DOCTYPE html>
<html lang='en'>
    <head>
        <meta charset='utf-8'>
        <meta content='IE=Edge,chrome=1' http-equiv='X-UA-Compatible'>
        <meta content='width=device-width, initial-scale=1.0' name='viewport'>
        <title>Buy Key- Order Confirm</title>
        <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
        <!--[if lt IE 9]>
              <script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.6.1/html5shiv.js" type="text/javascript"></script>
            <![endif]-->
        <link href="../../public/css/application.css" media="all" rel="stylesheet"
              type="text/css" />

    </head>
    <body>
<?php include '../navbar.php'; ?>
        <div class='container' id='content'>
            <h2>Order Confirmation</h2>
<?php if (isset($errorMessage)) { ?>
                <div class="alert fade in alert-error">
                    <button class="close" data-dismiss="alert">&times;</button>
    <?php echo $errorMessage; ?>
                </div>
            <?php } ?>
            <form accept-charset="UTF-8" method="post" action="order_confirmation.php"
                  class="simple_form form-horizontal new_order" id="order"
                  method="post" novalidate="novalidate">
                <div class='control-group'>
                    <label class="string optional control-label" for="order_amount">Prices</label>
                    <div class='controls'>
                        <label class='checkbox'> $<?php echo $amount; ?> </label> <input id="order_amount"
                                                                                       name="order[amount]" type="hidden" value="<?php echo $amount; ?>" />
                    </div>
                </div>
                <div class='control-group'>
                    <label class="string optional control-label" for="order_description">License type:</label>
                    <div class='controls'>
                        <label class='checkbox'> <?php echo $description; ?> </label> 
                    </div>
                </div>

                <div class='form-actions'>
                    <input class="btn btn btn-primary" name="commit" type="submit"
                           value="Place Order" />
                </div>
            </form>
<?php include '../footer.php'; ?>
        </div>
        <script src="../../public/js/application.js" type="text/javascript"></script>
    </body>
</html>