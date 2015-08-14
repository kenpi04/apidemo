<?php
/*
 * User sign in page.
 */
require_once __DIR__ . '/../bootstrap.php';
session_start();

unset($errorMessage);
// Sign in form postback
if($_SERVER['REQUEST_METHOD'] == 'POST') {
	try {
		if(validateLogin($_POST['user']['email'], $_POST['user']['password'])) {
			signIn($_POST['user']['email']);
			header("Location: ./index.php");
			echo "string";
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
<title>PizzaShop</title>
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
		<h2>Sign in</h2>
		<p>Sign in with your web.</p>
		<?php if(isset($errorMessage)) {?>
		<div class="alert fade in alert-error">
			<button class="close" data-dismiss="alert">&times;</button>
			<?php echo $errorMessage;?>
		</div>
		<?php }?>
		<form accept-charset="UTF-8" action="./login.php"
			class="simple_form form-horizontal new_user" id="new_user"
			method="post" novalidate="novalidate">			
			<div class="control-group email optional">
				<label class="email optional control-label" for="user_email">Username</label>
				<div class="controls">
					<input autofocus="autofocus" class="string optional"
						id="user_email" name="user[email]" size="50" type="email" value="">
				</div>
			</div>
			<div class="control-group password optional">
				<label class="password optional control-label" for="user_password">Password</label>
				<div class="controls">
					<input class="password optional" id="user_password"
						name="user[password]" size="50" type="password" />
				</div>
			</div>			
			<div class='form-actions'>
				<input class="btn btn btn-primary" name="commit" type="submit"
					value="Login" />
			</div>
		</form>
	</div>
	<?php include '../footer.php';?>
	<script src="../../public/js/application.js" type="text/javascript"></script>
</body>
</html>
