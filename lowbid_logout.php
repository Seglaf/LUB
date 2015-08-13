<?php

	session_start();
	session_destroy();

	header("Location: /lowbid/lowbid_site_login.php");
?>
