<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="css/bootsrap">
<link rel="stylesheet" href="css/bootsrap-responsive.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
<link rel="stylesheet" type="text/css" href="/lowbid/bidboard.css">
<link rel="stylesheet" type="text/css" href="/lowbid/animation.css">
<link rel="stylesheet" type="text/css" href="/lowbid/sidedisplay.css">
<link rel="stylesheet" type="text/css" href="/lowbid/lowbid_stylesheet.css">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script type="text/javascript">

	<script type="text/javascript" src="http://code.jquery.com/jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="http://ajax.microsoft.com/ajax/jquery.validate/1.7/jquery.validate.min.js"></script>
	<script type="text/javascript">
	function makeAccount(){

		$('#makeAccount').show();
	}	

	function login(){

		$('#makeAccount').hide();
	}

</script>

</head>

<?php
	session_start();
//	echo "<h4>WELCOME " . $_SESSION["username"] . "</h4>";

?>
<body id="productInfoBody" style='background-color: #F0FFFF'>
<?php
include "lowbid_render.php";
$username = "root";
$dbname = "lowbid_DB";
$pwd = "!jntmuffins4data"; //turn into pointers
$type = -1; #NO PRODUCT ID WILL EVER BE == -1
	
$render_page = new render_product($username, $dbname, $pwd, $type);


?>
</body>

</html>
