<?php
	if(isset($_SESSION['logged'])&&$_SESSION['logged']){
		$query = "SELECT * from tableName  ORDER BY `rfor`";
		$query_run = mysql_query($query);
		$pre='dummy';
		$arr=array();
		while($var = mysql_fetch_assoc($query_run)){
			if($pre!=$var['rfor']){
				if($var['rfor']!=''){
					array_push($arr, $var['rfor']);
					$pre = $var['rfor'];
			}
		}	
		print_r($arr);
?>
	<link rel="stylesheet" type="text/css" href="plugins/bootstrap/bootstrap.min.css">
	<table class="table table-striped">
	</table>

<?php
	}else{
		header("Location: clogin.php")
	}
?>