<?php

#This class will need continued security reinforcement
#Right now it is at a bear minimum for functionality purposes

class login{

	private $username = "root";
	private $dbname = "lowbid_DB";
	private $pwd = "!jntmuffins4data";

	private $db;
	private $user_name;
	private $user_pwd;	

	function __construct(){

		$this->user_name = $_POST['username'];
		$this->user_pwd = $_POST['password'];
		$this->connect_lowbid_DB();
		$this->security_check();
	}

	private function connect_lowbid_DB(){

	#	$this->db = new PDO(self::$dbname, self::$user, self::$pwd);
		$this->db = new PDO('mysql:host=localhost;dbname=' . $this->dbname . ';charset=utf8', $this->username, $this->pwd);
		#create exception/throw for later control over login procedure
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

