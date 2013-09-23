<?php 
session_start();
if (isset($_SESSION['username'])) {
header('Location: index.php');
}
$lv = $_GET['login'];
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Z&oacute;calo Group</title>
	<link rel="icon" href="favicon.ico" />
	<link href='http://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>
	<link type="text/css" rel="stylesheet" href="../blueprint/screen.css" media="screen, projection" />
	<link type="text/css" rel="stylesheet" href="../blueprint/print.css" media="print" />
	<!--[if lt IE 8]><link rel="stylesheet" href="../blueprint/ie.css" type="text/css" media="screen, projection"><![endif]-->
	<link type="text/css" rel="stylesheet" href="../css/style.css" media="screen" />
	<script src="./js/jquery-1.4.2.min.js" type="text/javascript"></script>
	<script src="./js/jquery.validate.pack.js" type="text/javascript"></script>
  </head>
<body class="login">
	<?php
	if ($lv === 'error') {
		print "<div class='wrap error'><img id='logo' src='images/logo.png' alt='Zocalo Group' /><h1>Login</h1><table id='loginForm' border='0'><form method='POST' action='loginproc.php'><tr><td colspan='3'><h4 class='error'>Oops! Try Again</h4></td></tr>";
	}
	else {
		print "<div class='wrap'><img id='logo' src='images/zocalo.png' alt='Zocalo Group' /><h1>Login</h1><table id='loginForm' border='0'><form method='POST' action='loginproc.php'>";
	} ?>

				<tr>
					<td><input type="text" name="username" size="20" placeholder="username"></td>
				</tr>
				<tr>
					<td><input type="password" name="password" size="20" placeholder="password"></td>
				</tr>
				<tr>
					<td><input class="btn" type="submit" value="Login"></td>
				</tr>
			</form>
		</table>
	</div>
</body>
</html>
