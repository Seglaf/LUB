<html>
<head>

<link rel="stylesheet" type="text/css" href="lowbid_stylesheet.css">
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="http://ajax.microsoft.com/ajax/jquery.validate/1.7/jquery.validate.min.js"></script>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
<link rel="stylesheet" type="text/css" href="/lowbid/bidboard.css">
<link rel="stylesheet" type="text/css" href="/lowbid/animation.css">
<link rel="stylesheet" type="text/css" href="/lowbid/login.css">
<link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
<script type="text/javascript">
</script>

</head>

<body>

<?php

	$username = $_POST['username'];
	$password = $_POST['password'];
	$secure_password = $_POST['securePassword'];
	$email = $_POST['email'];	

/*
	echo "<p>" . $username . "</p>";
	echo  "<p>" . $password . "</p>";
	echo  "<p>" . $secure_password . "</p>";
	echo "<p>" .  $email . "</p>";
*/

	if($password != $secure_password){

		echo "<h2> PLEASE MAKE SURE YOU RETYPED YOUR PASSWORD CORRECTLY </h2>";
		exit();
	}else{

		
	}
?>


<h2>PLEASE VERIFY YOUR EMAIL</h2>

</body>

</html>
