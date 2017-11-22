<?php

data_default_timezone_set('America/New_York');

$conn = new mysqli("classroom.cs.unc.edu", 
                   "elaineek", 
                   "Jennifer420!*", 
		           "elaineekdb");

$username = $_GET['username'];
$password = $_GET['password'];
$groupname = $_GET['groupname'];

$conn->query("insert into Users values (0, " .
	       "'" . $conn->real_escape_string($username) . "', " .
	       "'" . $conn->real_escape_string($password) . "', " .
	       "'" . $conn->real_escape_string($group_name) . ")");
$conn->query("insert into Groups values (0, " .
		   "'" . $conn->real_escape_string($group_name) . ")");
?>