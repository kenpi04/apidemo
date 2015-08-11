<?php
/*
 * User Forget Password page.
 */
require_once __DIR__ . '/../bootstrap.php';
session_start();

$newPassword = null;
// Forget Password form postback
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (trim($_POST['user']['email']) == '') {
        $errorMessage = "You must enter a email address to generate new password.";
    } else {
        try {
            $newPassword = uniqid();
            $userId = updateUser($_POST['user']['email'], $newPassword, null);
        } catch (Exception $ex) {
            $newPassword = null;
            $errorMessage = $ex->getMessage();
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
    <title>PizzaShop</title>
    <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
    <!--[if lt IE 9]>
    <script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.6.1/html5shiv.js" type="text/javascript"></script>
    <![endif]-->
    <link href="../../public/css/application.css" media="all" rel="stylesheet" type="text/css"/>
    <link href="../../public/images/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon"/>
</head>
<body>
<?php include '../navbar.php'; ?>
<div class='container' id='content'>
    <h2>Forgot Password</h2>
    <p>Generate New Password for an existing PizzaShop account.</p>
    <?php if (isset($errorMessage)) { ?>
        <div class="alert fade in alert-error">
            <button class="close" data-dismiss="alert">&times;</button>
            <?php echo $errorMessage; ?>
        </div>
    <?php } ?>
    <?php if ($newPassword) { ?>
        <h3>Your new password is: <?= $newPassword ?></h3>
    <?php } else { ?>
    <form accept-charset="UTF-8" action="./newpassword.php" autocomplete="off"
          class="simple_form form-horizontal generate_password" id="generate_password" method="post"
          novalidate="novalidate">
        <div style="margin:0;padding:0;display:inline"><input name="utf8" type="hidden" value="&#x2713;"/><input
                name="authenticity_token" type="hidden" value="vpVuNuIt9fRZzLm0eE0gk4h249k0nZPB/WEXWn9ETwg="/></div>

        <div class="control-group email required"><label class="email required control-label" for="user_email"><abbr
                    title="required">*</abbr> Email</label>

            <div class="controls"><input autofocus="autofocus" class="string email required" id="user_email"
                                         name="user[email]" size="50" type="email" value=""
                                         placeholder="dummy@email.com"/></div>
        </div>

        <div class='form-actions'>
            <input class="btn btn btn-primary" name="commit" type="submit" value="Generate New Password" />
        </div>
    </form>
    <?php } ?>
    <a href="sign_up.php">Sign Up</a><br />
    <a href="sign_in.php">Sign In</a><br />
</div>
<?php include '../footer.php';?>
<script src="../../public/js/application.js" type="text/javascript"></script>
</body>
</html>
