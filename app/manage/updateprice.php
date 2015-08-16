<?php
/*
 * User sign in page.
 */
require_once __DIR__ . '/../bootstrap.php';
session_start();
if(!isSignedIn()) {
	header('Location: ../manage/login.php');
	exit;
}


// Sign in form postback
if($_SERVER['REQUEST_METHOD'] == 'POST') {
	try {
	if(isset($_POST["delete"]))
		{
			
			DeletePrice($_POST["delete"]);
			echo "delete successfull!";
			exit;

		}
		if(isset($_POST["key"]["month"])&& isset($_POST["key"]["price"])) {
			$id=0;
			if(!isNull($_POST["key"]["id"]))
			{
				$id=(int)$_POST["key"]["id"];
			}

			InsertUpdatePrice($_POST["key"]["price"],(int)$_POST["key"]['month'],$id);

			
		} else {
			$errorMessage = "Error information not valid!";
		}		
	} catch (Exception $ex) {
		$errorMessage = $ex->getMessage();
	}
}
try {
	$priceList=getPriceList();
	
} catch (Exception $e) {
	$errorMessage=$e->getMessage();
	$priceList=array();
}
?>
<!DOCTYPE html>
<html lang='en'>
<head>
<meta charset='utf-8'>
<meta content='IE=Edge,chrome=1' http-equiv='X-UA-Compatible'>
<meta content='width=device-width, initial-scale=1.0' name='viewport'>
<title>update price</title>
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
		<h2>Update price</h2>
		
		<?php if(isset($errorMessage)) {?>
		<div class="alert fade in alert-error">
			<button class="close" data-dismiss="alert">&times;</button>
			<?php echo $errorMessage;?>
		</div>
		<?php }?>
		<form accept-charset="UTF-8" action="./updateprice.php"
			class="simple_form form-horizontal new_user" id="frmPrice"
			method="post" novalidate="novalidate">	
			<input name="key[id]" type="hidden" value="" id="hdId"/>	
			<input name="key[delete]" type="hidden" id="deleteId"/>
			<div id="div_id" class="control-group optional hidden">
				<label class="email optional control-label" for="key_month">Id </label>
				<div class="controls">
					<span id="span_id"></span>
				</div>
			</div>	
			<div class="control-group optional">
				<label class="email optional control-label" for="key_month">Key Exprire Months</label>
				<div class="controls">
					<input autofocus="autofocus" class="string optional required number"
						id="key_month" name="key[month]" size="50" type="number" maxlength="3" value="">
				</div>
			</div>
			<div class="control-group password optional">
				<label class="password optional control-label " for="key_price">Price</label>
				<div class="controls">
					<input class="password required" id="key_price"
						name="key[price]" size="50" type="number" maxlength="5" step="0" />
				</div>
			</div>			
			<div class='form-actions'>
				<input class="btn btn btn-primary" name="commit" type="submit"
					value="Create/Update" />
						<input class="btn btn btn-primary" name="commit" type="button"
					value="Cancel" onclick="window.location.reload()" />
			</div>
		</form>

		<p></p>
		<table class="table table-bordered">
			<tr>
				<th>
				Id
				</th>
				<th>
					Months
				</th>
				<th>
					Price
				</th>
				<th>
					
				</th>
			</tr>
			<?php foreach ($priceList as $price) { ?>
				<tr id="tr_<?php echo $price["id"] ?>">
						<td> <?php echo $price['id']?> </td>
							<td> <?php echo $price['MonthNumber']?> </td>
								<td> <?php echo $price['Price']?> </td>
								<td>
								<a class="button" onclick="updatePrice(<?php echo $price['id'] ?>)">Update</a> |
								<a class="button" onclick="deletePrice(<?php echo $price['id'] ?>)">Delete</a>
								 </td>
				</tr>
		<?php	} ?>

		</table>
	</div>
	<?php include '../footer.php';?>
	<script src="../../public/js/application.js" type="text/javascript"></script>
	<script src="../../public/js/jquery.validate.min.js" type="text/javascript"></script>
	<script type="text/javascript">
		$(function () {
			// body...
				$("#frmPrice").validate({

					 highlight: function(element) {
		                 $(element).addClass("f_error");
		                 
		             },
		             unhighlight: function(element) {
		                 $(element).removeClass("f_error");
		               
		             }

				});

				

		})
		function updatePrice (id) {
			// body4...
			var td=$("#tr_"+id +" td");
			$("#span_id").html(id);
			$("#hdId").val(id);
			var month=parseInt($(td[1]).html());
			var price=parseFloat($(td[2]).html());
			$("#key_month").val(month);
			$("#key_price").val(price);
			$("#div_id").removeClass("hidden");
		}
		function deletePrice(id)
		{
			$.post("./updateprice.php",{delete:id},function(){
				window.location.reload();
			})
			return false;
		}
	</script>
</body>
</html>
