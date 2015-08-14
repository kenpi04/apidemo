<?php
/*
 * User sign in page.
 */
require_once  'bootstrap.php';

session_start();
unset($errorMessage);
unset($keyresult);
unset($_SESSION['orderinfo']);
// Sign in form postback
if($_SERVER['REQUEST_METHOD'] == 'GET') {

	try{
			$pricesList=getPriceList();
	}
	catch(Exception $ex)
	{
		$pricesList= array();
		$errorMessage=$ex->getMessage();
	}

}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
	try {         	
             if(!isNull($_POST['reg']['email'])
             	&&!isNull($_POST['reg']['ime'])
             	&&!isNull($_POST['reg']['deviceid'])
             	&&!isNull($_POST['reg']['devicetype'])      
             	&&!isNull($_POST['reg']['licensetype'])         
             	)
             {
             		$daynumberLimit=(int)$_POST['reg']['monthnumber']*30;
             		$data=['email'=>$_POST['reg']['email'],'ime'=>$_POST['reg']['ime'],'deviceid'=>$_POST['reg']['deviceid'],'devicetype'=>$_POST['reg']['devicetype'],
						'creditcard_id'=>$creditCardId,'id'=>base64_encode($_POST['reg']['ime']),'price_id'=>$_POST['reg']['licensetype']
							];
             					setOrderInfo($data);		
             				header("Location: ./order/order_confirmation.php");
             				exit;
             	
             }
             else
             {
             	$errorMessage = "Fail, Please check your info!";
             }

		
	} catch (Exception $ex) {
		$errorMessage = $ex->getMessage();
	}
}
?>
<!DOCTYPE html>
<html lang='en'>
<head>
<meta charset='utf-8'>
<meta content='IE=Edge,chrome=1' http-equiv='X-UA-Compatible'>
<meta content='width=device-width, initial-scale=1.0' name='viewport'>
<title>Buy key</title>
<!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
<!--[if lt IE 9]>
      <script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.6.1/html5shiv.js" type="text/javascript"></script>
    <![endif]-->
<link href="../public/css/application.css" media="all"
	rel="stylesheet" type="text/css" />
<link href="../../public/images/favicon.ico" rel="shortcut icon"
	type="image/vnd.microsoft.icon" />
</head>
<body>
	<?php include 'navbar.php';?>
	<div class='container' id='content'>
		<h2>Payment Key</h2>
		<?php if(isset($errorMessage)) {?>
		<div class="alert fade in alert-error">
			<button class="close" data-dismiss="alert">&times;</button>
			<?php echo $errorMessage;?>
		</div>
		<?php }?>
                
                <?php if(isset($keyresult)) {?>
                <textarea class="form-control"><?php echo $keyresult;?></textarea>
                <?php }?>
                
                
		<form  accept-charset="UTF-8" action="index.php"
			class="simple_form form-horizontal new_user" id="new_user"
			method="post" novalidate="novalidate">		
			 <legend>Your device infomation </legend>	
			<div class="control-group email optional">
				<label class="email optional control-label" for="user_email"><abbr title="required">*</abbr>Email</label>
				<div class="controls">
					<input autofocus="autofocus" class="string email required"
						id="user_email" name="reg[email]" size="50" type="email" max-length="70" value="" placeholder="dummy@email.com"/>
				</div>
			</div>
			<div class="control-group ime optional">
				<label class="ime optional control-label" for="reg_ime"><abbr title="required">*</abbr>IME</label>
				<div class="controls">
					<input class="ime required" id="reg_ime"
						name="reg[ime]" size="20" max-length="15" type="text" />
				</div>
			</div>	
                    <div class="control-group deviceid optional">
				<label class="deviceid optional control-label" for="reg_deviceid"><abbr title="required">*</abbr>Device Id</label>
				<div class="controls">
					<input class="deviceid required" id="reg_deviceid"
						name="reg[deviceid]" size="50" max-length="250" type="text" />
				</div>
			</div>	
                      <div class="control-group devicetype optional">
				<label class="deviceid optional control-label" for="reg_devicetype">Type<abbr title="required">*</abbr></label>
				<div class="controls">
                                    <select class="deviceid required" id="reg_deviceid"
                                        name="reg[devicetype]">
                                        <option value="0">Android</option>     
                                         <option value="1">IOS</option>   
                                          <option value="2">Window phone</option>   
                                    </select>
				</div>
			</div>	
			 <div class="control-group devicetype optional">
				<label class="deviceid optional control-label" for="reg_devicetype">License type<abbr title="required">*</abbr></label>
				<div class="controls">
                                    <select class="deviceid required" id="reg_licenseId"
                                        name="reg[licensetype]">
                                        <option>Select license type</option>
                                        <?php foreach ($pricesList as $key) {?>
                                        	 <option data-price="<?php echo $key['Price'] ?>" value="<?php echo $key['id'] ?>"><?php echo $key['MonthNumber'] ?> Months</option>   
                                       <?php }?>
                                         
                                    </select>
                                 
				</div>
			</div>	
			<div class="control-group devicetype optional">
				<label class="deviceid optional control-label" for="reg_devicetype">Prices</label>
				<div class="controls">
                                   <span style="font-weight:bold;font-size:15px;color:red" id="span-price">-</span>                                 
				</div>
			</div>	
			
			<div class='form-actions'>
				<input class="btn btn btn-primary" name="commit" type="submit"
					value="Buy Key" />
			</div>
		</form>
              
	</div>
	<?php include 'footer.php';?>
	<script src="../public/js/application.js" type="text/javascript"></script>
	<script src="../public/js/jquery.validate.min.js" type="text/javascript"></script>
	<script type="text/javascript">
		$(function () {
			// body...
				$("#new_user").validate({

					 highlight: function(element) {
		                 $(element).addClass("f_error");
		                 
		             },
		             unhighlight: function(element) {
		                 $(element).removeClass("f_error");
		               
		             }

				});
				$("#reg_licenseId").change(function(){
					var price= $("#reg_licenseId option:selected").attr("data-price");
					$("#span-price").html("$"+price);
				})

		})
	</script>
</body>
</html>
