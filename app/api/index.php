<?php
/*
 * User sign in page.
 */
require_once __DIR__ . '/../bootstrap.php';
session_start();

unset($errorMessage);
unset($keyresult);
// Sign in form postback
if($_SERVER['REQUEST_METHOD'] == 'POST') {
	try {
             $keyresult=insertKey($_POST['reg']['email'], $_POST['reg']['ime'], $_POST['reg']['deviceid'], $_POST['reg']['typeid']);
		if($keyresult) {			
			header("Location: ../api/index.php");
			exit;
		} else {
			$errorMessage = "Login failed. Please check your username/password";
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
<title>Register key</title>
<!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
<!--[if lt IE 9]>
      <script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.6.1/html5shiv.js" type="text/javascript"></script>
    <![endif]-->
<link href="../../public/css/application.css" media="all"
	rel="stylesheet" type="text/css" />
<link href="../../public/images/favicon.ico" rel="shortcut icon"
	type="image/vnd.microsoft.icon" />
</head>
<body>
	<?php include '../navbar.php';?>
	<div class='container' id='content'>
		<h2>Payment key</h2>
		<?php if(isset($errorMessage)) {?>
		<div class="alert fade in alert-error">
			<button class="close" data-dismiss="alert">&times;</button>
			<?php echo $errorMessage;?>
		</div>
		<?php }?>
                
                <?php if(isset($keyresult)) {?>
                <textarea class="form-control"><?php echo $keyresult;?></textarea>
                <?php }?>
                
                
		<form accept-charset="UTF-8" action="./sign_in.php"
			class="simple_form form-horizontal new_user" id="new_user"
			method="post" novalidate="novalidate">			
			<div class="control-group email optional">
				<label class="email optional control-label" for="user_email">Email</label>
				<div class="controls">
					<input autofocus="autofocus" class="string email required"
						id="user_email" name="reg[email]" size="50" type="email" value="" placeholder="dummy@email.com"/>
				</div>
			</div>
			<div class="control-group ime optional">
				<label class="ime optional control-label" for="reg_ime">IME</label>
				<div class="controls">
					<input class="ime required" id="reg_ime"
						name="reg[ime]" size="20" type="text" />
				</div>
			</div>	
                    <div class="control-group deviceid optional">
				<label class="deviceid optional control-label" for="reg_deviceid">Device Id</label>
				<div class="controls">
					<input class="deviceid required" id="reg_deviceid"
						name="reg[deviceid]" size="50" type="text" />
				</div>
			</div>	
                      <div class="control-group devicetype optional">
				<label class="deviceid optional control-label" for="reg_devicetype">Type</label>
				<div class="controls">
                                    <select class="deviceid required" id="reg_deviceid"
                                        name="reg[devicetype]" size="50">
                                        <option value="0">Android</option>     
                                         <option value="1">IOS</option>   
                                          <option value="2">Window phone</option>   
                                    </select>
				</div>
			</div>	
			<div class='form-actions'>
				<input class="btn btn btn-primary" name="commit" type="submit"
					value="Sign in" />
			</div>
		</form>
              
	</div>
	<?php include '../footer.php';?>
	<script src="../../public/js/application.js" type="text/javascript"></script>
</body>
</html>
