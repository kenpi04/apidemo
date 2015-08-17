<?php
require_once __DIR__ . '/../bootstrap.php';
session_start();
if(!isSignedIn()) {
	header('Location: ../manage/login.php');
	exit;
}
$pageIndex=1;
		$pageSize=20;
		$totalItem=0;

if($_SERVER['REQUEST_METHOD'] == 'GET') {
	try {
		$searchfield="";
		$searchvalue="";
		if(isset($_GET["searchfield"]) &&!isNull($_GET["searchfield"]))
			$searchfield=$_GET["searchfield"];
		if(isset($_GET["searchfield"])&&!isNull($_GET["searchvalue"]))
			$searchvalue=$_GET["searchvalue"];
		if(isset($_GET["pageindex"])&&!isNull($_GET["pageindex"]))
			$pageIndex=$_GET["pageindex"];

		$keys = getListKey($searchfield,$searchvalue, $pageIndex,$pageSize,$totalItem);
		$totalpage=ceil($totalItem/$pageSize);
	} catch (Exception $ex) {
		// Don't overwrite any message that was already set
		if(!isset($message)) {
			$message = $ex->getMessage();
			$messageType = "error";
		}
		$keys = array();
	}

}
?>
<!DOCTYPE html>
<html lang='en'>
<head>
<meta charset='utf-8'>
<meta content='IE=Edge,chrome=1' http-equiv='X-UA-Compatible'>
<meta content='width=device-width, initial-scale=1.0' name='viewport'>
<title>Key management</title>
<!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
<!--[if lt IE 9]>
      <script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.6.1/html5shiv.js" type="text/javascript"></script>
    <![endif]-->
<link href="../../public/css/application.css" media="all"
	rel="stylesheet" type="text/css" />

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
		<h2>Key Manage</h2>
		<div class="row">
			<form class="form-inline" role="form" action="./index.php" method="get">
  <div class="form-group">

  	<table class="table">
  		<tr>
  			<td>
  				<label for="email">Search field:</label>
  			</td>
  			<td>
  				<select class="form-control" name="searchfield" id="frmSearch">
    	<option value="email">Email</option>
    	<option value="IME_Code">IME</option>
    	<option value="Device_id">Device Id</option>
    	<option value="Key_code">Key</option>    	
    	<option value="payerid">Payment Id</option>
    </select>
  			</td>
  			<td>
  				<label>Search value</label>
  			</td>
  			<td>
  				   <input type="text" class="form-control required" name="searchvalue">
  			</td>
  			<td>
  					<a class="btn btn-default" href="./updateprice.php">Update Prices</a>
  				<a class="btn btn-default" href="./index.php">Reset search</a>
  				  <button type="submit" class="btn btn-default">Search</button>
  <input type="hidden" name="pageindex" value="<?php echo $pageIndex; ?>"/>
  			</td>
  		</tr>
  	</table>
    
    
  </div>

</form>
		</div>
		<table class='table table-bordered'>
			<thead>
				<tr>
					<th>Id</th>
					<th>Email</th>
					<th>Device Id</th>
					<th>IME</th>
					<th>Key</th>
					<th>Active Date</th>
					<th>End Date</th>
					<th>Device type</th>
					<th>Payment Id</th>
				</tr>
			</thead>			
			<tbody>
				<?php foreach($keys as $key) {?>
				<tr <?php echo ((int)$key['DateCount']<=0?'class="error"':"") ?> >
					<td><?php echo $key['id'] ?></td>
					<td><?php echo $key['email'] ?></td>
					<td><?php echo $key['Device_Id'] ?></td>
					<td><?php echo $key['IME_Code'] ?></td>
					<td><?php echo $key['key_code'] ?></td>
					<td><?php echo $key['CreateDate'] ?></td>
					<td><?php echo $key['DateLimit'] ?> </td>
							<td><?php $i= (int)$key['Type'] ;
								if($i==0)
									echo "Android";
								else if($i==1)
									echo "IOS";
								else
									echo "Window phone";


							?> </td>

					<td><?php echo $key['PayerId'] ?></td>
				</tr>
				<?php }?>
			</tbody>
		</table>
		<?php if($totalpage>1){ ?>
		<div class="pagination">
			<ul>
				<?php 
					$startPage=($pageIndex-3>1?$pageIndex:1);
					$endPage=($pageIndex+3<$totalpage?$pageIndex:$totalpage);
				for($i=$startPage;$i<=$endPage ;$i++) 

					{ ?>
					<li><a page="<?php echo $i;  ?>" href="#"><?php echo $i; ?></a></li>
				<?php } ?>
 		 
 
</ul>
			<span><?php echo $pageIndex; ?> / <?php echo $totalpage; ?></span>
		
		</div>
		<?php } ?>
	</div>
	<?php include '../footer.php';?>
	<script src="../../public/js/application.js" type="text/javascript"></script>
	<script type="text/javascript">
		$(function(){
			$("a[page]").click(function(){
				var page=$(this).attr("page");
				$('input[name="pageindex"]').val(page)
				$("#frmSearch").submit();
			})
		})
	</script>
</body>
</html>