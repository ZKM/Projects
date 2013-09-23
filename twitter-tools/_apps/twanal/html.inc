<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title>Twanal 1.0</title>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
		<link rel="icon" href="favicon.ico" />
		<style type="text/css">
			input, textarea {width: 200px;}
			form {width:200px; margin: 50px auto; text-align:center;}
			img {border-width: 0; display: block;}
			img:hover {opacity: .7;}
			a {color: #FF0000; text-decoration: none;}
			a:hover {text-decoration: underline;}
			em {font-size: 80%; text-transform: lowercase; font-style: normal;}
		</style>	
	</head>
	<body>
	<?php 
		if (isset($menu)) {
			echo $menu;
		}
		if (isset($status_text)) {
			echo '<h3>'.$status_text.'</h3>';
		}

		print($content);

		?>

		<!-- FORM that collects followers information from a single Screen Name -->
		<form id="twanal" action="user_search.php" method="POST"> 
			<label for="follower_search">Enter Screenname: <br>
			<textarea name="screen_name"></textarea></label>
			<input type="hidden" name="date" value="<?php print(date("Y/m/d, h:s A")); ?>">
			<input type="submit" value="Submit"> 
		</form> 
		<form id="twollors" action="user_search.php" method="POST"> 
			<label for="follower_search">Enter Screenname: <br>
			<textarea name="screen_name"></textarea></label>
			<input type="hidden" name="date" value="<?php print(date("Y/m/d, h:s A")); ?>">
			<input type="submit" value="Submit"> 
		</form> 
	</body>
</html>
