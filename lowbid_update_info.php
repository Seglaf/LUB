<?php

class UPDATE extends render_product{

	private $username;
	private $dbname;
	private $pwd;
	private $selected_product_ID;

	private $db;

	private $current_winner;
	private $array_HUB;
	private $LUB;

	function __construct(){
	

		render_product::connect_lowbid_DB();

		session_start();
		$this->user_ID = $_SESSION['user_ID'];

		$this->username = func_get_arg(0);
		$this->dbname = func_get_arg(1);			
		$this->pwd = func_get_arg(2);	
		$this->selected_product_ID = func_get_arg(3);
		$this->connect_lowbid_DB();
	} 

	private function connect_lowbid_DB(){

	#	$this->db = new PDO(self::$dbname, self::$user, self::$pwd);
		$this->db = new PDO('mysql:host=localhost;dbname=' . $this->dbname . ';charset=utf8', $this->username, $this->pwd);
		#create exception/throw for later control over login procedure
	}

	private function query_game_value($column){

		$query = $this->db->prepare("SELECT $column FROM game WHERE FK_product=$this->selected_product_ID;");
		$query->execute
		return $query->fetchColumn();
	}

	private function update_game(){

		$this->current_winner = $this->query_game_value("winning_player");
		
		$this->LUB = $this->query_game_value("LUB");
		$this->array_HUB = unserialize($this->query_game_value("HUB"));		
		$this->render_update();
	}

	private function render_update(){

		echo "<p>THE CURRENT WINNER IS: $this->current_winner</p>";
		echo "<p>THERE ARE " . count($this->array_HUB) . " HUBs</p>";
	}

 
}	

?>
