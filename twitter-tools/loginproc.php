<?php
session_start();
include('con.php');

// Retrieve username and password from database according to user's input
$login = mysql_query("SELECT * FROM login WHERE username = '" . mysql_real_escape_string($_POST['username']) . "' and password = '" . mysql_real_escape_string(md5($_POST['password'])) . "'");

// Check username and password match
if (mysql_num_rows($login) == 1) {
	$_SESSION['username'] = $_POST['username'];
	header('Location: index.php');
//	var_dump($_SESSION['username']);
//	var_dump($_POST['username']);
}
else {
	header('Location: login.php?login=error');
}

?>