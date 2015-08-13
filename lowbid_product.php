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

<!--/lowbid/lowbid_form_handler.php/?product=" . $this->selected_product_ID . "-->
	<title>Product Bid</title> 
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="http://ajax.microsoft.com/ajax/jquery.validate/1.7/jquery.validate.min.js"></script>
	<script type="text/javascript">

	function updateUserBidCount(){
		var userBids = announceValues();

		var userBidCount = document.getElementById("userBidCount");
	//	var userBids = document.querySelectorAll("#bidform div[name='bids[]']:active"); //Returns an array

		var total_attempt = parseInt(userBidCount.innerHTML) + userBids.length; 
		var bidCount = document.getElementById("bidCount"); 
		if(total_attempt <= parseInt(bidCount.innerHTML)){

			if(total_attempt >= parseInt(bidCount.innerHTML)){
			
				alert("TOO MANY BIDS MATE");
			}

			userBidCount.innerHTML = total_attempt;
		}
		
	}

	function attemptClearBoard(){
	alert("YOYO");	
		var bids = document.querySelectorAll("#bidform div[name='bids[]']");
		var userBidCount = document.getElementById("userBidCount");
		if(parseInt(userBidCount.innerHTML) == 0){
				alert("IN");	
			for(i = 1; i <= bids.length; i++){

		//		if($( "#box_" + i ).is(":disabled")){	
					$( "#box_" + i	).toggleClass("disabled");
					alert(i);
		//		}	

			}
		}
	}	
	
	function checkImmediateBidCount(){
		var userBids = announceValues();

		var userBidCount = document.getElementById("userBidCount");
	//	var userBids = document.querySelectorAll("#bidform div[name='bids[]']:active"); //Returns an array

		var total_attempt = parseInt(userBidCount.innerHTML) + userBids.length; 
		var bidCount = document.getElementById("bidCount"); 
		if(total_attempt > parseInt(bidCount.innerHTML)){
			
		
		}
	}


	function lockSelectedBids(){
		var resultBox = document.getElementById("resultBox");			
		var bids = document.querySelectorAll("#bidform div[name='bids[]']");
		for(i = 1; i <= bids.length; i++){

			if($( "#box_" + i ).hasClass("active")){	

				$( "#box_" + i ).toggleClass("disabled");
				$( "#box_" + i ).toggleClass("btn-info");
				$( "#box_" + i ).toggleClass("active");
			}
		}
	}

	function randomPaint(){
//AZURE IS WINNING
		
		var colors = ["AliceBlue","AntiqueWhite","Aqua","Aquamarine","Azure","Beige","Bisque","Black","BlanchedAlmond","Blue","BlueViolet","Brown","BurlyWood","CadetBlue","Chartreuse","Chocolate","Coral","CornflowerBlue","Cornsilk","Crimson","Cyan","DarkBlue","DarkCyan","DarkGoldenRod","DarkGray","DarkGrey","DarkGreen","DarkKhaki","DarkMagenta","DarkOliveGreen","Darkorange","DarkOrchid","DarkRed","DarkSalmon","DarkSeaGreen","DarkSlateBlue","DarkSlateGray","DarkSlateGrey","DarkTurquoise","DarkViolet","DeepPink","DeepSkyBlue","DimGray","DimGrey","DodgerBlue","FireBrick","FloralWhite","ForestGreen","Fuchsia","Gainsboro","GhostWhite","Gold","GoldenRod","Gray","Grey","Green","GreenYellow","HoneyDew","HotPink","IndianRed","Indigo","Ivory","Khaki","Lavender","LavenderBlush","LawnGreen","LemonChiffon","LightBlue","LightCoral","LightCyan","LightGoldenRodYellow","LightGray","LightGrey","LightGreen","LightPink","LightSalmon","LightSeaGreen","LightSkyBlue","LightSlateGray","LightSlateGrey","LightSteelBlue","LightYellow","Lime","LimeGreen","Linen","Magenta","Maroon","MediumAquaMarine","MediumBlue","MediumOrchid","MediumPurple","MediumSeaGreen","MediumSlateBlue","MediumSpringGreen","MediumTurquoise","MediumVioletRed","MidnightBlue","MintCream","MistyRose","Moccasin","NavajoWhite","Navy","OldLace","Olive","OliveDrab","Orange","OrangeRed","Orchid","PaleGoldenRod","PaleGreen","PaleTurquoise","PaleVioletRed","PapayaWhip","PeachPuff","Peru","Pink","Plum","PowderBlue","Purple","Red","RosyBrown","RoyalBlue","SaddleBrown","Salmon","SandyBrown","SeaGreen","SeaShell","Sienna","Silver","SkyBlue","SlateBlue","SlateGray","SlateGrey","Snow","SpringGreen","SteelBlue","Tan","Teal","Thistle","Tomato","Turquoise","Violet","Wheat","White","WhiteSmoke","Yellow","YellowGreen"];
		var randomColor = colors[Math.floor(Math.random() * colors.length)];

		$('#productInfoBody').css('background-color', randomColor);
	}
	

	function announceValues(){
		var bidArray = document.getElementsByName('bids[]');
		var userBidArray = new Array();
		for(i = 0; i < bidArray.length; i++){
	
		//	alert(bidArray[i].hasClass("active"));

			if($(bidArray[i]).hasClass("active")){
//THIS IF CONDITION IS NOT BEING ENTERED, MAKE SURE ELEMENTS ARE ACTUALLY ACTIVE WHEN SELECTED.
				userBidArray.push(bidArray[i].firstChild.value);
			}

		}

		return userBidArray;

	}


	function contains(arr, obj){
		for(i = 0; i < arr.length; i++){

		//	if(arr[i].firstChild.value == obj){
			if(arr[i] == obj){
				return true;
			}
		}
	
		return false;
	}

	function checkUBs(HUBs, NUBs, LUB){

		for(i = 0; i < HUBs.length; i++){

			$('#' + HUBs[i]).toggleClass('btn-warning');
		}
		for(i = 0; i < NUBs.length; i++){

			$('#' + NUBs[i]).toggleClass('btn-danger');
		}
//		if(LUB != NULL){}
		$('#' + LUB).toggleClass('btn-success');
	}

	$(document).ready(function(){
	//	attemptClearBoard();
//		announceValues();
		$('[id^=box_]').click(function() {
			$(this).toggleClass("active");
			$(this).toggleClass("btn-info");
//			announceValues();
		});
//		randomPaint();

		$("#bidform").submit(function(e) {
	
			var userBidArray = announceValues();
			var prod = document.getElementById("productID").value;
			//VALIDATION CONDITION VVV
			if(userBidArray == ""){

				alert("PLEASE SELECT A BID BEFORE SUBMITTING");
				return false;
			}
			var formUrl = "/lowbid/lowbid_form_handler.php/";	
	//CHANGE FOR PROGRESS BAR		updateUserBidCount();
			$.ajax({
				url : "/lowbid/lowbid_form_handler.php",
				type : "POST",
				data : { productID : prod, userBids : userBidArray},
				success : function(formHandlerData){
					if(formHandlerData.oldLUB != null){

					document.getElementById(formHandlerData.oldLUB).className = "myButton btn sharp btn-warning";

}

					alert(formHandlerData.test);
					if(formHandlerData.notEnuffTokens != null){

					}else{
						document.getElementById('bidTokens').innerHTML = formHandlerData.bidTokens;//ADD TO ABSTRACT FUNCTION???
					}
					
					checkUBs(formHandlerData.HUBs, formHandlerData.NUBs, formHandlerData.LUB);
			//		alert(formHandlerData.LUB);
			//		alert(formHandlerData.foundLUB);
					$('#results').html(formHandlerData.renderHtml);
					lockSelectedBids();
					$('#progressBar').style.width = formHandlerData.progress;	
					$('#progressBar').innerHTML = formHandlerData.progress + '%';
				},
				dataType : "json"
			});
			e.preventDefault();
			e.unbind();
		});


	});


	</script>

</head>
<!--<body style='background-color: lightblue;'>-->
<body id='productInfoBody'>
<!-- MAKE TOOLBAR SEPERATE FILE -->

<div class="" id="entiregame">

<!--<p style="color: green;">HELLO WORLD</p>-->
<?php     
include 'lowbid_render.php';
$username = "root";
$dbname = "lowbid_DB";
$pwd = "!jntmuffins4data";
$selected_product_ID = $_GET['product'];
session_start();
$myusername = $_SESSION['username'];

$db_control = new render_product($username, $dbname, $pwd, $selected_product_ID); 

?>

<div name="fourth_info" class="" id="results"></div>
</div>
</body>

</html>
