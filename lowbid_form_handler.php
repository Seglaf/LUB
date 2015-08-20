<?php


class SUBMIT_BID{

	private static $dbuser = 'root';
	private static $dbname = 'lowbid_DB';
	private static $pwd = '!jntmuffins4data';

	private $is_logged;
	private $user_bids;
	private $selected_product_ID;
	private $user_ID;
	private $username;
	private $db;
	
	private $LUB;
	private $found_LUB;
	private $old_LUB;
	private $array_HUB = array();
	private $array_NUB = array();
	private $range_min;

	#JSON ENCODE VALUES
	private $html;
	private $ID_HUBs = array();
	private $ID_NUBs = array();
	private $ID_LUB;

	private $test;
	private $bid_type;
	private $bid_price;
	private $bid_ID;

	function __construct(){

		session_start();
		$this->user_ID = $_SESSION['user_ID'];
		$this->username = $_SESSION['username'];
		$this->selected_product_ID = $_POST['productID'];
		$this->user_bids = $_POST['userBids'];	
		
//		$animation_class = "bounceIn animated row";
//		$this->html .= "<div class='announceLUB " . $animation_class . "'>YOU OWN THE LOWEST UNIQUE BID OF</div>";
		
//		$value = 42;
//		$this->html .= $value;
		$this->db = new PDO('mysql:host=localhost;dbname=' . self::$dbname . ';charset=utf8', self::$dbuser, self::$pwd);
		$this->bid_tokens = $this->query_user_value('bid_token_count');
		
		if($this->bid_tokens > 0 && $this->bid_tokens != NULL && ($this->bid_tokens - count($this->user_bids)) >= 0){

			if($this->LUB_gameplay()){
		//		$thiswest_unique_bid();//REPLACE WITH LUB_gameplay();
	//TO REFRESH ALL UBs INCASE OF EDIT=======================================
				$allUBs = $this->query_gameplay_value('bid_ID', "product_ID='$this->selected_product_ID';");
	//			$allUBs = $this->db->prepare("SELECT bid_ID FROM gamplay WHERE product_ID='$this->selected_product_ID';");		
	//=========================================================================	
				echo json_encode(array('renderHtml' => $this->html, 'HUBs' => $this->ID_HUBs, 'NUBs' => $this->ID_NUBs, 'LUB' => $this->ID_LUB, 'newLUB' => $this->new_LUB, 'oldLUB' => $this->old_LUB, 'foundLUB' => $this->found_LUB, 'bidTokens' => $this->bid_tokens, 'notEnuffTokens' => NULL, 'test' => $this->test, 'progress' => $this->progress));
			}else{

			//ERROR	
				echo json_encode(array('test' => $this->test));
			}
		}else{

			echo json_encode(array('notEnuffTokens' => 'YOU DO NOT HAVE ENOUGH BID TOKENS PLEASE PURCHASE MORE'));
		}
			
			
		
	}
//CONSIDER: What if we 
//NEW GAMEPLAY TABLE FUNCTION WORKZONE====================================
	private function record_bid($user_bid){
//USE [] instead of ARRAY_PUSH
		if($this->bid_type == "NUB"){

			$query = $this->db->prepare("UPDATE gameplay SET bid_type='NUB' WHERE bid_price=$user_bid AND product_ID=$this->selected_product_ID;");
		}


		$query = $this->db->prepare("INSERT INTO gameplay (bid_ID, bid_price, bid_type, user_ID, product_ID, username) VALUES('$this->bid_ID', $user_bid, '$this->bid_type', '$this->user_ID', $this->selected_product_ID, '$this->username' );"); //$this->bid_ID, $this->bid_price, $this->bid_type, $this->user_ID);");
		$query->execute();
	}

//ENTERING ANY CONDTIONS WITHIN THIS FUNCTION WILL PREVENT GAMEPLAY
	private function LUB_gameplay_preconditions($total_user_bids, $bid_cap){
 
		//CHECK IF $this->user_bids CONATINS ANY DUPLICATES
		//REFRESH GAMEPLAY TO CORRECT ANY USER DUPLICATES


		if($total_user_bids > $bid_cap){

			$this->test = "YOU MADE TOO MANY BIDS!";
			if($this->query_product_value('sold') == 'YES'){
				$this->test = "THE GAME HAS ENDED PLEASE PLAY ANOTHER GAME!";	
			}
		}

		$bid_token_count = $this->query_user_value('bid_token_count');
		if($bid_token_count <= 0){

			$this->test = "YOU DON'T HAVE ENOUGH BETS, PLEASE SELECT BUY BIDS IN YOUR TOOLBAR";
		}
	
		$user_copy_count = $this->query_gameplay_value('COUNT(*)', "user_ID='$this->user_ID' AND username='$this->username' AND product_ID=$this->selected_product_ID AND bid_price IN (" . implode(',', $this->user_bids) . ");");	
		if($user_copy_count > 0){

			$this->test = "YOU HAVE ALREADY MADE THAT BID";
		}

		$range_max = $this->query_product_value('range_max');

		foreach($this->user_bids as $user_bid){

			if(($user_bid % $this->range_min) != 0 || $user_bid > $range_max){

				$this->test = "NOT A VALID BID VALUE";	
			}elseif($user_bid > $range_max){

				$this->test = "BID OUT OF RANGE";
			}
		}
	
	}

//MOVE
	private function query_gameplay_value($column, $search){

		$query = $this->db->prepare("SELECT $column FROM gameplay WHERE $search");
		$query->execute();
		return $query->fetchColumn();
	}


	private function LUB_gameplay(){
//NOW CORRECT USER RENDER.PHP
		$this->test = NULL; //change name
		$this->new_LUB = NULL;
		$this->old_LUB = NULL;

		$user_bid_count = $this->query_product_value('user_bid_count');
		$bid_count = $this->query_product_value('bid_count');
		$this->range_min = $this->query_product_value('range_min');
		$user_bid_count += sizeof($this->user_bids);//change this variable name that is being added to, doesn't make much sense
		$this->progress = round(($user_bid_count / $bid_count) * 100); //ONLY PROGRESS UPDATE FOR JSON -> AJAX

		$this->LUB_gameplay_preconditions($user_bid_count, $bid_count);		
		if($this->test != NULL){

			return false;
		}
		$new_game = $this->query_gameplay_value('bid_ID', "product_ID=$this->selected_product_ID LIMIT 1"); //$this->db->prepare("SELECT bid_ID FROM gameplay WHERE product_ID=$this->selected_product_ID LIMIT 1");
//		$new_game->execute();
//		$new_game = $new_game->fetchColumn()

		if(!($new_game)){
			
			$new_game = true;
		}else{

			$new_game = false;
		}

		foreach($this->user_bids as $user_bid){

			$this->bid_ID = 'box_' . ($user_bid / $this->range_min);
			//$price_count = $this->db->prepare("SELECT COUNT(*) FROM gameplay WHERE product_ID=$this->selected_product_ID AND bid_ID='$this->bid_ID';");
			//$price_count->execute();
			//$price_count = $price_count->fetchColumn();
			$query = $this->db->prepare("SELECT COUNT(*) FROM gameplay WHERE product_ID=$this->selected_product_ID AND bid_ID='$this->bid_ID';");
			$query->execute();
			$price_count = $query->fetchColumn();
			if($price_count >= 1){
			
				$this->bid_type = 'NUB';
				$this->ID_NUBs[] = $this->bid_ID;
				if($price_count == 1){
//IF FOR LUB EXISTENCE, IF == 1 signifies a LUB or HUB is being KILLED
					$bid_type = $this->query_gameplay_value('bid_type', "bid_price=$user_bid AND product_ID=$this->selected_product_ID;"); //GETS BID TYPE OF current $user_bid
					if($bid_type == 'LUB' && $this->found_LUB != true){ //mysql returns false if nothing is found
						if($this->query_gameplay_value('COUNT(*)', "bid_type='HUB' AND product_ID=$this->selected_product_ID;") > 0){
	
							$this->change_LUB();	
						}
					}			
				}	
				$query = $this->db->prepare("UPDATE gameplay SET bid_type='NUB' WHERE bid_price=$user_bid AND product_ID=$this->selected_product_ID;");//SET ALL LIKE VALUES TO NUB
				$query->execute();

			}else{

				//CHECK IF LUB
				$current_LUB = $this->query_gameplay_value('bid_price', "bid_type='LUB' AND product_ID=$this->selected_product_ID");
//				$current_LUB = $this->db->prepare("SELECT bid_price FROM gameplay WHERE bid_type='LUB' AND product_ID=$this->selected_product_ID;");

//				$current_LUB->execute();
//				$current_LUB = $current_LUB->fetchColumn();

				if(($user_bid < $current_LUB || !($current_LUB)) && $this->found_LUB != true){//THINK THROUGH AND CONDITION
				
					$this->bid_type = 'LUB';	
					$this->ID_LUB = $this->bid_ID;
					$this->found_LUB = true;

					$query = $this->db->prepare("UPDATE gameplay SET bid_type='HUB' WHERE bid_type='LUB' AND product_ID=$this->selected_product_ID;");
					$query->execute();
//CHECK FOR OTHER LUB TO CHANGE TO HUB==============================================================================
					$this->old_LUB = $this->query_gameplay_value('bid_ID', "bid_type='LUB' AND product_ID=$this->selected_product_ID AND user_ID='$this->user_ID';");
//					$query = $this->db->prepare("SELECT bid_ID FROM gameplay WHERE bid_type='LUB' AND product_ID=$this->selected_product_ID;");
//					$query->execute();

//					$this->old_LUB = $query->fetchColumn();
					if(!($this->old_LUB)){//$new_game){
					
						$this->old_LUB = NULL;
					}else{
	
						$query = $this->db->prepare("UPDATE gameplay SET bid_type='HUB' WHERE bid_ID='$this->old_LUB' and product_ID=$this->selected_product_ID;");  //MAYBE ISSUE
						$query->execute();
					}//END OF LUB->HUB CHECK=========================================================================
//ELSE HUB
				}else{

					$this->bid_type = 'HUB';
					$this->ID_HUBs[] = $this->bid_ID;
		//			$this->old_LUB = NULL;
				}

			}

			$this->record_bid($user_bid);//MORE OR LESS PARAMETERS??
		}
		$this->deduct_tokens(sizeof($this->user_bids), $bid_count);
		$this->generate_game_results();


//FIX!!!! SEPERATE FUNCTION====================================================	
		if($user_bid_count == $bid_count){

			$this->end_gameplay();
		}


		$query = $this->db->prepare("UPDATE product SET user_bid_count=$user_bid_count WHERE product_ID=$this->selected_product_ID;");
		$query->execute();

		return true;
	}

	private function change_LUB(){
	
		$lowest_HUB = $this->query_gameplay_value('MIN(bid_price)', "bid_type='HUB' AND product_ID=$this->selected_product_ID;");//$query->fetchColumn();
		$this->new_LUB = $this->query_gameplay_value('bid_ID', "bid_price=$lowest_HUB AND bid_type='HUB' AND user_ID='$this->user_ID' AND product_ID=$this->selected_product_ID");
		if(!($this->new_LUB)){

			$this->new_LUB = NULL;
		}

		$query = $this->db->prepare("UPDATE gameplay SET bid_type='LUB' WHERE bid_price=$lowest_HUB AND product_ID=$this->selected_product_ID;");	
		$query->execute();
	
	}	
	private function end_gameplay(){
//WINNER DETERMINED BY OWNED OF LUB
//WINNER STORED IN PRODUCT TABLE

			$query = $this->db->prepare("UPDATE product SET sold='YES' WHERE product_ID=$this->selected_product_ID;");
			$query->execute();

			$winner = $this->query_gameplay_value('username', "bid_type='LUB' AND product_ID=$this->selected_product_ID;");

			$query = $this->db->prepare("UPDATE product SET winner='$winner' WHERE product_ID=$this->selected_product_ID;");
			$query->execute();
	}

	private function generate_game_results(){

		$animation_class = "bounceIn animated row";
		if($this->found_LUB){
	
			$this->html .= "<div class='announceLUB " . $animation_class . "'>YOU OWN THE LOWEST UNIQUE BID OF $this->LUB</div>";
		}else{

			$this->html .= "<div class='missedLUB " . $animation_class . "'>YOU MISSED THE LOWEST UNIQUE BID</div>";
		}

		foreach($this->array_HUB as $HUB){

			$this->html .= "<div class='annouceHUB " . $animation_class . "'>YOU OWN A HIGHER UNIQUE VALUE: $HUB</div>";
		}

		foreach($this->array_NUB as $NUB){

			$this->html .= "<div class='annouceNUB " . $animation_class . "'>YOU MADE $NUB NOT UNIQUE</div>";
		}
		
		if($this->user_bid_count == $this->bid_count){

			$this->html .= "<div class='annouceCap " . $animation_class . "'>THE CAP IS REACHED THE GAME HAS ENDED</div>";
		}
	}

//========================================================================

	
	private function query_product_value($column){

		$query = $this->db->prepare("SELECT $column FROM product WHERE product_ID=" . $this->selected_product_ID . ";");
		$query->execute();
		return $query->fetchColumn();
	}

	private function query_user_value($column){

		$query = $this->db->prepare("SELECT $column FROM user WHERE user_ID='$this->user_ID' AND username='$this->username';");
		$query->execute();
		return $query->fetchColumn();
	}

	private function build_bid_stack(){
		#Instead of double array change to key orientated.
		$stack_board_bids = unserialize($this->query_product_value("selected_bids"));
		if(is_array($stack_board_bids)){

			return $stack_board_bids;
		}else{

		//	$this->build_game_record();
			$stack_board_bids = array();
			$row_size = 10;//need this?
			$element_count = 1; //or this???

			$bid_increment = $this->query_product_value("bid_increment");
			$range_max = $this->query_product_value("range_max");
			$size = $range_max / $this->range_min;
			for($i = 1; $i <= $size; $i++){

				$price = $bid_increment * $i;
				$inner_bid = array();
				array_push($inner_bid, $price, 0);
				array_push($stack_board_bids, $inner_bid);				
			}
	//		$this->html .= "<p>YOUR STACK: " .  . "</p>";
			return $stack_board_bids;
		}
	}

//DELETE__________________________________________________
	private function lowest_unique_bid(){
	
		$this->range_min = $this->query_product_value("range_min");
		$current_user_bids = count($this->user_bids);
		$total_user_bids = ($this->query_product_value('user_bid_count') + $current_user_bids);
		$bid_cap = $this->query_product_value('bid_count');
		$is_capped = $this->check_cap($total_user_bids, $bid_cap);
		$this->deduct_tokens($current_user_bids);
		$stack_board_bids = $this->build_bid_stack();
		$this->LUB = $this->query_product_value('winning_bid');

		foreach($stack_board_bids as &$bid){

			foreach($this->user_bids as $user_bid){

				if($user_bid == $bid[0]){
					$this->bid_price = $user_bid;
					$this->bid_ID = 'box_' . ($user_bid / $stack_board_bids[0][0]);
					$is_LUB = $this->check_if_LUB($user_bid, $bid[1]);
					if($is_LUB){
						$this->bid_type = 'LUB';
						$this->found_LUB = true;
					}else{
						$this->check_if_HUB($user_bid, $bid[1]);	
						$this->LUB = $this->check_if_NUB($stack_board_bids, $user_bid, $bid[1]);  #$this->LUB is set to the return value in case the LUB becomes a NUB so LUB is replaced by the nearest HUB.
					}

					$bid[1]++; //INSERT
				}
			}
		}
			
		$this->post_board_response();
		$this->store_user_values();  #consider passing arguments rather than accessing properties.
		$this->store_product_values($total_user_bids, $stack_board_bids, $is_capped); #dependent on $this->LUB.
	}

	private function deduct_tokens($current_user_bids){
	
		$this->bid_tokens -= $current_user_bids;
		$query = $this->db->prepare("UPDATE user SET bid_token_count=$this->bid_tokens WHERE user_ID='$this->user_ID';");
		$query->execute();

	}

	private function store_product_values($total_user_bids, $stack_board_bids, $is_capped){

		if($this->found_LUB){

			$query = $this->db->prepare("UPDATE product SET winning_bid=$this->LUB WHERE product_ID='$this->selected_product_ID';");
			$query->execute();
		}

		$query = $this->db->prepare("UPDATE product SET user_bid_count=$total_user_bids WHERE product_ID='$this->selected_product_ID';");
		$query->execute();

		$serial_array = serialize($stack_board_bids); #depending on size you may need to compress this as well to be more efficiently stored in the DB.
		$query = $this->db->prepare("UPDATE product SET selected_bids='$serial_array' WHERE product_ID='$this->selected_product_ID';");
		$query->execute();

		if($is_capped){

			$query = $this->db->prepare("UPDATE product SET sold='YES' WHERE product_ID='$this->selected_product_ID';");
			$query->execute();
		}

	}


	private function store_user_values(){

		$stored_user_bids = unserialize($this->retrieve_user_values());
		if(is_array($stored_user_bids)){

			foreach($this->user_bids as $user_bid){

				array_push($stored_user_bids, $user_bid);
			}
			$serial_array = serialize($stored_user_bids);
			$query = $this->db->prepare("UPDATE user SET array_serial_$this->selected_product_ID='$serial_array' WHERE user_ID='$this->user_ID';");
			$query->execute();
		}else{

			$query = $this->db->prepare("ALTER TABLE user ADD array_serial_$this->selected_product_ID VARCHAR(8000) NULL");
			$query->execute();
			$stored_user_bids = $this->user_bids;
			$serial_array = serialize($stored_user_bids);
			$query = $this->db->prepare("UPDATE user SET array_serial_$this->selected_product_ID='$serial_array' WHERE user_ID='$this->user_ID';");
			$query->execute();
		}
	}

	private function retrieve_user_values(){

		$query = $this->db->prepare("SELECT array_serial_$this->selected_product_ID FROM user WHERE user_ID='$this->user_ID';");
		$query->execute();
		return $query->fetchColumn();
#		echo $query->fetchColumn();
	}

//DELETE?
	private function post_board_response(){
//		$this->html .= "HELLO";
	#Depending on how you want the user to be given results to their bid submits, edit this function accordingly.
	#Add addtional conditions as well as bootstrap class names.
		$animation_class = "bounceIn animated row";
		if($this->found_LUB){

			
			$this->html .= "<div class='announceLUB " . $animation_class . "'>YOU OWN THE LOWEST UNIQUE BID OF $this->LUB</div>";
/*DOUBLE CHECK */ //	$this->ID_LUB = "box_" . (floatval($this->LUB) / $this->range_min);
		}else{
	$this->html .= "<div class='missedLUB " . $animation_class . "'>YOU MISSED THE LOWEST UNIQUE BID</div>";
		}

/*
		foreach($this->array_HUB as $HUB){

			array_push($this->ID_HUBs, "box_" . (floatval($HUB) / $this->range_min));//ERROR!!
			$this->html .= "<div class='annouceHUB " . $animation_class . "'>YOU OWN A HIGHER UNIQUE VALUE: $HUB</div>";
		}
*/
/*
		foreach($this->array_NUB as $NUB){

			array_push($this->ID_NUBs, "box_" . (floatval($NUB) / $this->range_min));
			$this->html .= "<div class='annouceNUB " . $animation_class . "'>YOU MADE $NUB NOT UNIQUE</div>";
		}
*/		
		if($this->is_capped){

			$this->html .= "<div class='annouceCap " . $animation_class . "'>THE CAP IS REACHED THE GAME HAS ENDED</div>";
		}

//		$this->html .= "<p>" . $this->ID_HUBs[0] . "</p>";
	}


	private function check_if_LUB($user_bid, $bid_tally){

		if($this->LUB == NULL){

			$this->LUB = $user_bid;
			return true;
		}		
		elseif($this->LUB > $user_bid && $bid_tally == 0){

			$this->LUB = $user_bid;
			return true;
		}
		else{

			return false;
		}		
	}

	private function check_if_HUB($user_bid, $bid){
		#add condition for LUB lost yet still HUB
		$this->html .= "<p>$user_bid</p>";
		if($bid == 0 && $user_bid > $this->LUB){
		
			array_push($this->array_HUB, $user_bid);
			$this->bid_type = 'HUB';	
		}
	}

	private function check_if_NUB($stack_board_bids, $user_bid, $bid){

#		echo "<p>ARGUMENTS OF check_if_NUB: " . var_dump($stack_board_bids) . ", $user_bid, $bid</p>";

		if($bid >= 1){

			array_push($this->array_NUB, $user_bid);
			$this->bid_type = 'NUB';
			if($this->LUB == $user_bid){

				foreach($stack_board_bids as $bid){

					#$bid[1] == $this->user_ID;
					if($bid[0] > $this->LUB && $bid[1] == 1){

						return $bid[0];
					}
				}
				return NULL;
			}
		}
		return $this->LUB;
		#CREATE CONDITION FOR LUB->NUB AND MAKING HUB->LUB
	}
}

$form_handler = new SUBMIT_BID();


?>
