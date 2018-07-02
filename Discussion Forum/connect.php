<?php
function doDB() {
	global $mysqli;

	//
	// connect to server and select database
	//
	$mysqli = mysqli_connect("localhost", "root", "", "MarchMadness");

	//if connection fails, stop script execution
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}
	// Start the session
	session_start();
}
?>
