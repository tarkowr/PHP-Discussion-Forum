<?php
function doDB() {
	global $mysqli;

	//
	// connect to server and select database
	// Uncomment the correct connection string for use
	//

	//$mysqli = mysqli_connect("localhost:3306", "lisabalbach_tarkowr", "CIT1802511", "lisabalbach_testdb");
	//$mysqli = mysqli_connect("localhost:3306", "lisabalbach_scooby", "CIT1995*", "lisabalbach_testdb");
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