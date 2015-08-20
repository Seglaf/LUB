<?php

#This class will need continued security reinforcement
#Right now it is at a bear minimum for functionality purposes
class login{

	private $dbuser = "root";
	private $dbname = "lowbid_DB";
	private $pwd = "!jntmuffins4data";

	private $db;
	private $user_name;
	private $user_pwd;	

	private $username;
	private $password;
	private $secure_password;
	private $email;

	function __construct(){
	session_start();
	session_destroy();

			$this->user_name = $_POST['username'];
			$this->user_pwd = $_POST['password'];
//		$type = $_GET['action'];

		$this->connect_lowbid_DB();

	/*	if($type == 'createAccount'){

			$this->username = $_POST['username'];
			$this->password = $_POST['password'];
			$this->secure_password = $_POST['securePassword'];
			$this->email = $_POST['email'];	
			$this->create_account();	
		}else{*/
			$this->security_check();
	//	}
	}

	private function connect_lowbid_DB(){

	#	$this->db = new PDO(self::$dbname, self::$user, self::$pwd);
		$this->db = new PDO('mysql:host=localhost;dbname=' . $this->dbname . ';charset=utf8', $this->dbuser, $this->pwd);
		#create exception/throw for later control over login procedure
	}

	private function create_account(){
	
		if($password != $secure_password){

			echo "<h2> PLEASE MAKE SURE YOU RETYPED YOUR PASSWORD CORRECTLY </h2>";
			exit();
		}else{

			$query = $this->db->prepare("INSERT INTO user (username, password, email) VALUES ($this->username, $this->password, $this->email);");	
			$query->execute();
		}
	}

	private function security_check(){


		$query = $this->db->prepare("SELECT username FROM user WHERE username='$this->user_name';");
		$query->execute();
		$check_user = $query->fetchColumn();

		$query = $this->db->prepare("SELECT password FROM user WHERE password='$this->user_pwd';");
		$query->execute();
		$check_pwd = $query->fetchColumn();
		echo "<p>$check_user, $check_pwd</p>";			

		
		if($this->user_name == NULL || $this->user_pwd == NULL){

			header('Location: /lowbid/lowbid_site_login.php');
			echo "<script type='text/javascript' >alert($message);</script>";
			exit();
		}

		if($check_user == $this->user_name && $check_pwd == $this->user_pwd){
			session_start();
			$_SESSION['username'] = $this->user_name;
			$_SESSION['password'] = $this->user_pwd;
			
			$query = $this->db->prepare("SELECT user_ID FROM user WHERE username='$this->user_name';");
			$query->execute();
			$_SESSION['user_ID'] = $query->fetchColumn(); 
	
			header('Location: /lowbid/lowbid_product_display.php');
		}else{
			#doesn't show fix this user prompt error.
			echo "<p>INCORRECT LOGIN INFORMATION</p>";
			header('Location: /lowbid/lowbid_site_login.php');

		}
	}


}

$login_check = new login();

?>

