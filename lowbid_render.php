<?php


class render_product{
	#make these more secure
	private $username;
	private $dbuser;
	private $dbname;
	private $pwd;
	#db variable
	private $db;
	#Necessary Product Values To Render
	private $user_ID;
	private $user_selected_items;
	private $user_previous_bids;
	private $selected_product_ID;
	private $product_name;
	private $bid_increment;
	private $range_min;
	private $range_max;
	private $sold;
	private $bid_count;
	private $user_bid_count;
	private $progress;
	private $product_image;
	private $found_LUB;

	private $product_count;
	function __construct(){
		session_start();
		$this->user_ID = $_SESSION['user_ID'];
		$this->username = $_SESSION['username'];
#		$this->user_ID = 'lub0001';

		$this->dbuser = func_get_arg(0);
		$this->dbname = func_get_arg(1);			
		$this->pwd = func_get_arg(2);	
		$this->selected_product_ID = func_get_arg(3);
		$this->connect_lowbid_DB();

		#this seems a bit disorganized at changing the functioning of this object.
		#maybe have just one object that we call on through pointers
		#instead of creating a new on in memory everytime we create a new page.
		#check this after minimal functionality is reached.

		if(func_get_arg(3) == -1){//MAKE A STRING CHECK

			$this->render_header();
			$this->render_product_display();	
		}else{
	
			$this->query_user_previous_bids();
			$this->query_user_bids();
			$this->render_header();
		//	$this->render_header_update();
			$this->render_product_info(); //CHANGE TO query_product_info();	
			$this->render_page();	//col-md-5
			$this->render_product_form(); //col-md-7
		}
	}


	private function render_page(){

#MAKE THIS FUNCTION MORE DYNAMIC
#Add any additional lines for alternative html renders.
	#	echo "<link rel='stylesheet' type='text/css' href='lowbid_stylesheet.css'>";
		echo "<div class='col-md-5'>";
		echo "<div id='productInfo'>";
		echo "<div class=''>";
		echo "<h3>$this->product_name</h3>";
		echo "<p>Bid Increment of $this->bid_increment </p>";
		echo "<p>Minimum: $this->range_min </p>";
		echo "<p>Maximum: $this->range_max </p>";
		if($this->sold == 'YES'){
			echo "<p>This product has been sold! </p>";
		}else{
			echo "<p>This product has yet to be sold. </p>";
		}
	
		

		echo "<div class='progress'>
		  <div id='progressBar' class='active progress-bar progress-bar-success progress-bar-striped' role='progressbar' aria-valuenow='s' aria-valuemin='0' aria-valuemax='100' style='width:$this->progress%;'>
		    $this->progress%
		  </div>
		</div>";
		echo "</div>";
		echo "</div>";

		$this->render_side_display();
		echo "</div>";
/*
		echo "<div id='resultBox'>";

		foreach($this->user_selected_items as $item){

			echo"<div id='resultBoxItem' >$item</div>";
		}		


		echo "</div>";
*/
	}

	private function lock_user_selection($price, $HUBs, $NUBs, $LUB){
//CHANGES ALL NECESSARY TILES OF SPECIFIC USER TO BID TYPE COLOR
//CHANGE ONCE DOUBLE ARRAY IS TURNED TO DICTIONARY......
		if($this->query_product_value("user_bid_count") == $this->query_product_value("bid_count")){
			
			return " '  disabled";
		}

		if($this->user_ID == NULL){

			return " btn-primary' disabled";
		}elseif(in_array($price, $HUBs)){
		//LUB COLOR CHANGE
			return " btn-warning' disabled";						
		

		}elseif(in_array($price, $NUBs)){
		//HUB COLOR CHANGE
			return " btn-danger' disabled";

		}elseif($LUB == $price){
		//NUB COLOR CHANGE
			$this->found_LUB = true;
			return " btn-success' disabled";

		}	

		return " btn-primary'";
	}
//===================================***RENDER BID BOARD/FORM***====================
	private function render_product_form(){

//		$query = $this->db->prepare("SELECT bid_price FROM gameplay WHERE product_ID=$this->selected_product_ID AND bid_type='LUB'
		$this->user_UBs($HUBs, $NUBs, $LUB);	
		$total_elements = $this->range_max / $this->range_min;
		$total_rows = ceil($total_elements / 10);
		$element_count = 1;
		echo "<div class='col-md-7'>";
		if($this->query_product_value("user_bid_count") == $this->query_product_value("bid_count") && $this->user_ID != NULL){
			$winner = $this->db->prepare("SELECT winner FROM product WHERE product_ID=$this->selected_product_ID");
			$winner->execute();
			$winner = $winner->fetchColumn();

		    echo "<div class='center-y row'>
			<div class='col-md-4'>
				<div class='animated bounceInDown container panel panel-warning' style='z-index:1000; position: fixed; '>
			<h2> WINNER IS $winner </h2>
			</div>
			</div>
			</div> ";			
		}
		echo "<form method='POST' class='bidform' id='bidform'>";
		echo "<input type='submit' class='btn btn-block btn-lg btn-success' id='submitBidForm' name='bid' value='bid'/>";
		echo "<div class='table-responsive'>";
		echo "<table class='table'>";
		for($i = 0; $i < $total_elements; $i++){//$total_rows; $i++){

	//	echo "<tr>";
		echo "<div class='btn-group' role='group'>";
//			echo "<tr>";
	//		for($j = 0; $j < 10; $j++){

				$price = strval(number_format($this->bid_increment * $element_count, 2, '.', ''));
				$lock_type = $this->lock_user_selection($price, $HUBs, $NUBs, $LUB);
//				echo "<td>";
				echo "<div type='button' name='bids[]' id='box_$element_count' class='myButton btn sharp$lock_type><input type='hidden' value='$price'>$price</input></div>";
		//		echo "<input type='checkbox' name='bids[]' id='box_$element_count' value='$price' " . $is_locked . "$price</span></input>";
//				echo "</td>";
				$element_count++;
	//		}
		echo "</div>";
	//	echo "</tr>";
//			echo "</tr>";
		}

			
		
		echo "</table>";
		echo "</div>";
//CHANGE TO STATIC ENDGAME DIV ONCE 100%
		echo "<input type='hidden' id='productID' name='productID' value='" . $this->selected_product_ID . "' />";
		echo "<input type='submit' class='btn btn-block btn-lg btn-success' id='submitBidForm' name='bid' value='bid'/>";
		echo "</form>"; 
		echo "</div>";
	}

//==================================***
	private function query_from($table, $column){
	//USE THIS FUNCTION TO REPLACE ALL BASIC QUERIES

		$query = $this->db->prepare("SELECT $column FROM $table WHERE user_ID='$this->user_ID';");
		$query->execute();
		return $query->fetchColumn();
	}

	private function render_header_update(){
	
		echo "<div class='navbar-header'>
		      <button type='button' class='navbar-toggle collapsed' data-toggle='collapse' data-target='#bs-example-navbar-collapse-1' aria-expanded='false'>
			<span class='sr-only'>Toggle navigation</span>
			<span class='icon-bar'></span>
			<span class='icon-bar'></span>
			<span class='icon-bar'></span>
		      </button>
		      <a class='navbar-brand' href='#'>Brand</a>
		    </div>";

	}	

	private function render_header(){
	//add image to account tab
		echo "<div class='navbar navbar-default'>";
		echo " <div class='container-fluid'>";
		echo "	<div id='navbar' class='navbar-collapse collapse'>
			    <ul class='nav navbar-nav'>
		<!-- CHANGE HREFS LATER!!! MAKE SURE THIS NAVBAR MAKES SENSE-->
			      <li class='active'><a href='/lowbid/lowbid_login.php/'>Home</a></li>
			      <li class='active'><a href='/lowbid/lowbid_product_display.php/'>Bid</a></li>
			      <li class='active'><a href='/lowbid/lowbid_buy_bids.php'>Buy Bids</a></li>
			      <li class='active'><a href='/lowbid/lowbid_earn_bids.php'>Earn Bids</a></li>
			      <li class='active'><a href='/lowbid/lowbid_product_sell.php'>Sell</a></li>
			      <li class='active'><a href='/lowbid/lowbid_how_to.php'>How to</a></li>";
		if($this->user_ID != NULL){
		echo "<li class='active'><a href='/lowbid/lowbid_account.php'>Account <small>$this->username </small></a></li>
			      <li class='active'><a href='/lowbid/lowbid_logout.php'>logout</a></li>";
		}else{

			echo "<li class='active'><a href='/lowbid/lowbid_site_login.php'>Login</a></li>";
		}

		echo  "<li class='active'><a href='/lowbid/lowbid_search.php'>Search</a></li>
				  </ul>
			  </div>
		</div>";
		echo "</div>";
		if($this->user_ID == NULL){
//Login prompt for guest accessing user pages
		echo "<div id='loginPrompt notLoggedIn' class='container'>
		    <div class='row vertical-center-row'>
			<div class=''>
			    <div class='row'>
				<div class='container panel panel-warning col-xs-4 col-xs-offset-4' style='margin-top: 8%; height:50%; z-index:1000; position: fixed; '>
		<div class='panel-heading text-center'><br/>YOU MUST LOGIN TO BID<br/><br/></div>
		<div class='panel-body'></div>
		<div class='form'>
			<form id='loginForm' action='/lowbid/lowbid_login.php' method='POST' >
			<div class='row'>
				<input  class='col-md-4 col-md-offset-4' type='text' name='username' placeholder='Username' /> 
			</div>

			<div class='row'>
				<input class='col-md-4 col-md-offset-4' type='password' name='password' placeholder='Password' /> 	
			</div>
			<div class='row'>
				<input class='col-md-4 col-md-offset-4 btn btn-primary' type='submit' id='loginButton' value='Login'  >	
			</div>
			</form>

			<h5 class='text-center'><a onclick='makeAccount()' href='#' >Create an Account</a></h5>
			
		<div id='makeAccount' class='form animated bounceInUp' style='display: none;'>
			<form id='accountForm' action='/lowbid/lowbid_create_account.php' method='POST' >
				<input type='text' name='username' placeholder='Username' /> 
				<input type='password' name='password' placeholder='Password' /> 
				<input type='password' name='securePassword' placeholder='Repeat Password' /> 
				<input type='text' name='email' placeholder='Email' />
				<input class='btn btn-success' type='submit' id='loginButton' value='Create Account'  >
				
			</form>
		</div>

		</div>
	</div>				
		<a href='/lowbid/lowbid_site_login.php'><h2>PLEASE LOGIN TO PLAY</h2></a>
				</div>
			</div>
		    </div>
		</div>	";

		}else{

			echo "<marquee>";
			echo "<p>WELCOME TO LOWBID UNCONVENTIONAL BIDDING! GOOD LUCK, $this->username</p>";
			echo "</marquee>";
		}
	}

	private function render_side_display(){
	//	echo "HELLO";

		$query = $this->db->prepare("SELECT COUNT(*) FROM product;");
		$query->execute();
		$this->product_count = $query->fetchColumn();	
		$array_display = array();		
		$this->select_random_products($array_display);
		$user_token_count = $this->query_from('user', 'bid_token_count');
	
		echo "<div class='container-fluid sideDisplay'>"; // id='sideDisplay'>";	
		echo "<div class='sideProductDisplay'>"; //possibly add 'row' to class,  id='sideProductDisplay' >";
		if($this->user_ID != NULL){

			echo "<div class='panel'>";
			echo "<div class='panel-body'>";	
			echo "<h2> " . $this->query_from('user', 'username') . " <small> <a href='/lowbid/lowbid_logout.php'>LOGOUT</a> </small> </h2>";
			echo "<h3> <small> BID TOKENS: <span id='bidTokens'>$user_token_count</span> </small></h3>";	
			echo "<h4> <small> HUBs OWNED: <span id='HUBCount'> </span> </small></h4>";
			echo "<h4> <small> NUBs OWNED: <span id='NUBCount'> </span> </small></h4>";
			echo "<h4> <small> LUB OWNED: <span id='LUBOwned'> </span> </small></h4>";
			echo "</div>";
			echo "</div>";
		}
		echo "<div class='row'>";
		foreach($array_display as $prod){
			echo "<a class='' href='/lowbid/lowbid_product.php/?product=$prod'>";
			echo "<div id='product_$prod' class='col-md-4 panel panel-default'>";
			echo "	<div class='btn-block hero-feature thumbnail' syle='border-color: none !important;'>";
							
			echo	"<img src='http://placehold.it/800x500' alt=''>
					LOOK AT PRODUCT $prod
				</div>";
			echo "</div>";
			echo "</a>";
		}	
		echo "</div>";
		echo "</div>";
		echo "</div>";
	}	
 
	private function select_random_products(&$array_display){
	
		#instead of all random make some relative to the popularity shown in the bid, or bids that are close to closing, or bids that the user has invested in <---- THIS ONE FOR SURE.	
		$rand_product = rand(1, $this->product_count);

		if(count($array_display) == 3){
		
			return $array_display;
		}elseif(in_array($rand_product, $array_display) || $rand_product == $this->selected_product_ID){

			$this->select_random_products($array_display);
		}else{
			
			array_push($array_display, $rand_product);
			$this->select_random_products($array_display);
		}
	}

	private function render_user_info(){

		#ALL DEM USER STATS FROM DA TABLE
		#$this->render_user_info();
	}

	private function connect_lowbid_DB(){

	#	$this->db = new PDO(self::$dbname, self::$user, self::$pwd);
		$this->db = new PDO('mysql:host=localhost;dbname=' . $this->dbname . ';charset=utf8', $this->dbuser, $this->pwd);
	}

	private function query_user_previous_bids(){

		$query = $this->db->prepare("SELECT selected_bids FROM product WHERE product_ID='" . $this->selected_product_ID . "';");
		$query->execute();
		$this->user_previous_bids = unserialize($query->fetchColumn());
	}

	private function query_product_value($column){

		$query = $this->db->prepare("SELECT $column FROM product WHERE product_id=" . $this->selected_product_ID . ";");
		$query->execute();
		return $query->fetchColumn();
	}

	private function query_user_bids(){

//		$query = $this->db->prepare("SELECT bid_price FROM gameplay WHERE user_ID='$this->user_ID' AND product_ID=$this->selected_produect_ID;");
		$query = $this->db->prepare("SELECT array_serial_$this->selected_product_ID FROM user WHERE user_ID='" . $this->user_ID . "';");
		$query->execute();
		$this->user_selected_items = unserialize($query->fetchColumn());
	//	echo var_dump($this->user_selected_items);
	}

	private function render_product_display(){

		$query = $this->db->prepare("SELECT COUNT(*) FROM product;");
		$query->execute();
		$product_count = $query->fetchColumn();
	
		$row_count = ceil($product_count / 3);
		$render_count = 1;
		$item_count = 3;
		for($i = 0; $i < $row_count; $i++){
			echo "<div class='row text-center'>";
		//	$this->deduct_tokens(sizeof($this->user_bids));
		//	$this->generate_game_results();
		//	if($product_count - $render_count < 3){
		//		$item_count = ($product_count - $render_count) + 1;
		//	}
			for($j = 0; $j < $item_count; $j++){ 
				if($render_count <= $product_count){

				 //	$result = mysql_query("SELECT product_ID, product_name FROM product WHERE product_ID=$render_count");
				//	$product_info = mysql_fetch_row($result);
					
					$query = $this->db->prepare("SELECT * FROM product WHERE product_ID=$render_count");
					$query->execute();
					$product_info = $query->fetch();				
				
					$this->progress = ceil(($product_info['user_bid_count'] / $product_info['bid_count']) * 100); 
					echo "<div class='col-md-4 col-sm-8 hero-feature'>
						<div class='thumbnail'>
						<img src='http://placehold.it/800x500' alt=''>";

						echo "<div class='row'>";
						echo "<div class='caption'>";
						echo 	"<h3>" .  $product_info['product_name'] . "</h3>";
						echo "</div>";
						echo "</div>";

					echo "<div class='row'>";
					echo 	"<div class='col-md-3 panel-body'>";
					echo		"MIN: " . $product_info['range_min'];
					echo 	"</div>";
					echo 	"<div class='col-md-6 panel-body'>";
						echo "<div class='progress'>
						  <div id='progressBar' class='active progress-bar progress-bar-success progress-bar-striped' role='progressbar' aria-valuenow='s' aria-valuemin='0' aria-valuemax='100' style='width:$this->progress%;'>
						    $this->progress%
						  </div>
						</div>";
					echo 	"</div>";
					echo 	"<div class='col-md-3 panel-body'>";
					echo		"MAX: " . $product_info['range_max'];
					echo 	"</div>";
					echo "</div>";

						echo "<div class='row'>";
						echo "<a href='/lowbid/lowbid_product.php/?product=$render_count'>";
						if($product_info['sold'] == 'NO'){

							echo "<div class='col-md-2 col-md-offset-5 btn btn-info'>";
							echo 	"PLAY";
							echo "</div>";
						}else{

							echo "<div class='col-md-2 col-md-offset-5 btn btn-success' disabled>";
							echo 	"SOLD";
							echo "</div>";
						}
						echo "</a>";
						echo "</div>";

					echo 	"</div>
					</div>";
/*	
					echo "<div class='col-md-4'>";
					echo "<div class='thumbnail'>";
				
					echo "<div class='row text-center'>";
					echo "<div class='col-md-12'>";
					echo 	$product_info['product_name'];
					echo "</div>";
					echo "</div>";
	
					echo "<div class='row'>";
					echo "<div class='progress'>
					  <div id='progressBar' class='active progress-bar progress-bar-success progress-bar-striped' role='progressbar' aria-valuenow='s' aria-valuemin='0' aria-valuemax='100' style='width:$this->progress%;'>
					    $this->progress%
					  </div>
					</div>";
					echo "</div>";

					echo "<div class='row panel panel-default'>";
					echo 	"<div class='col-md-4 panel-body'>";
					echo		"MIN: " . $product_info['range_min'];
					echo 	"</div>";
					echo 	"<div class='col-md-4 panel-body'>";
					echo		$product_info['product_image'];
					echo 	"</div>";
					echo 	"<div class='col-md-4 panel-body'>";
					echo		"MAX: " . $product_info['range_max'];
					echo 	"</div>";
					echo "</div>";

					echo "<div class='row'>";
					echo "<a href='/lowbid/lowbid_product.php/?product=$render_count'>";
					if($product_info['sold'] == 'NO'){

						echo "<div class='col-md-2 col-md-offset-5 btn btn-info'>";
						echo 	"PLAY";
						echo "</div>";
					}else{

						echo "<div class='col-md-2 col-md-offset-5 btn btn-success' disabled>";
						echo 	"SOLD";
						echo "</div>";
					}
					echo "</a>";
					echo "</div>";

					echo "</div>";
					echo "</div>";*/
					$render_count++;
				}else{

					$render_count = NULL;
					
					echo "<div class='col-md-4'>"; //ADD XS, LG, SM
					echo "EMPTY";
					echo "</div>";
				}
			}
			echo "</div>";
		}

		
	}

	private function render_product_info(){

		#call constrol render dependencies
		$this->product_name = $this->query_product_value("product_name");
		$this->bid_increment = $this->query_product_value("bid_increment");
		$this->range_min = $this->query_product_value("range_min");
		$this->range_max = $this->query_product_value("range_max");
		$this->sold = $this->query_product_value("sold");
		$this->bid_count = $this->query_product_value("bid_count");
		$this->user_bid_count = $this->query_product_value("user_bid_count");
		$this->progress = ceil(($this->user_bid_count / $this->bid_count) * 100);
		$this->product_image = $this->query_product_value("product_image");
	}



//================================***PULL ALL UNIQUE BIDS***====================		
	private function user_UBs(&$HUBs, &$NUBs, &$LUB){

		$query = $this->db->query("SELECT bid_price FROM gameplay WHERE bid_type='HUB' AND user_ID='$this->user_ID' AND product_ID=$this->selected_product_ID");
		$HUBs = $query->fetchAll(PDO::FETCH_COLUMN);
//		$HUBs = mysql_fetch_array($query);

		$query = $this->db->query("SELECT bid_price FROM gameplay WHERE bid_type='NUB' AND user_ID='$this->user_ID' AND product_ID=$this->selected_product_ID");
		$NUBs = $query->fetchAll(PDO::FETCH_COLUMN);

		$query = $this->db->prepare("SELECT bid_price FROM gameplay WHERE bid_type='LUB' AND user_ID='$this->user_ID' AND product_ID=$this->selected_product_ID ORDER BY time DESC LIMIT 1;");
		$query->execute();
		$LUB = $query->fetchColumn();
	}


}

#TEST VALUES
#$username = "root";
#$dbname = "lowbid_DB";
#$pwd = "!jntmuffins4data";
#$selected_product_ID = $_GET['product'];
#$test = new render_product($username, $dbname, $pwd, $selected_product_ID);


?>
