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

	function makeAccount(){

		$('#makeAccount').show();
	}	

	function login(){

		$('#makeAccount').hide();
	}

</script>

	

</head>

<body class='bodyFont'>
	<div>		
		<div class='form animated bounceInDown text-center'>
			<h2 style='margin-bottom: 10%'>Lowbid Login</h2>
			<form id='loginForm' action='/lowbid/lowbid_login.php' method='POST' >
				<input type='text' name='username' placeholder='Username' /> 
				<input type='password' name='password' placeholder='Password' /> 
				<input class='btn btn-success' type='submit' id='loginButton' value='Login'  >
			</form>

			<h5 style='margin-top: 10%;'>Not a Member? <a onclick="makeAccount()" href='#make_account' ><h5>Create an Account</h5></a>
		</div>

		<div id='makeAccount' class='form animated bounceInUp' style='display: none;'>
			<form id='accountForm' action='/lowbid/lowbid_create_account.php' method='POST' >
				<input type='text' name='username' placeholder='Username' /> 
				<input type='password' name='password' placeholder='Password' /> 
				<input type='password' name='securePassword' placeholder='Repeat Password' /> 
				<input type='text' name='email' placeholder='Email' />
				<input class='btn btn-success' type='submit' id='loginButton' value='Create Account'  >
				
			</form>
			<h5><a onclick="login()" href='#' >Login</a></h5>
		</div>
	</div>

</body>

</html>
