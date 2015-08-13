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

<script type="text/javascript">

</script>


</head>

<body>
	<div>		
		<div class='form animated bounceInDown'>
			<h2 >Lowbid Login</h2>
			<form id='loginForm' action='/lowbid/lowbid_login.php' method='POST' >
				<input type='text' name='username' placeholder='Username' /> 
				<input type='password' name='password' placeholder='Password' /> 
				<input type='submit' id='loginButton' value='Login'  >
			</form>

		</div>
	</div>

</body>

</html>
