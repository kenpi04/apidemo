<?php 
$basePath = (strstr($_SERVER['PHP_SELF'], "/index.php")) ? "." : "..";
require_once  'bootstrap.php';
?>
<?php if(isSignedIn()){ ?>
<div class='navbar navbar-static-top'>
	<div class='navbar-inner'>
		<div class='container'>
			<ul class="nav navbar-nav">
        <li><a href="../manage/index.php">Manage key</a></li>
        <li><a href="../manage/updateprice.php">Update price</a></li>
        </ul>
		</div>
	</div>
</div>
<?php } ?>
