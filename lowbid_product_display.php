<html>
<head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
<link rel="stylesheet" type="text/css" href="lowbid_stylesheet.css">
<link rel="stylesheet" type="text/css" href="bidboard.css">



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
